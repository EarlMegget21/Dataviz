import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.net.URL;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.Locale;
import java.util.stream.IntStream;



public class Crawler {
    //Date
    static Locale localeFr = Locale.FRANCE;
    static SimpleDateFormat formatter = new SimpleDateFormat("dd MMM yyyy",localeFr);
    //SQL
    static String myDriver = "com.mysql.jdbc.Driver";
    static String myUrl = "jdbc:mysql://guiltycore.fr:3306/dataviz?useSSL=false";


    public static void main(String[] args) {



        //Contiendra tout le contenu de toutes les pages HTML
        ArrayList<String> htmlContent = new ArrayList<>();

        //Ajoute du contenue des 774 pages dans htmlContent
        IntStream.range(1,775)
                .forEach(integer->
                        htmlContent.add(
                                getURLContent("http://rendezvousavecmrx.free.fr/page/detail_emission.php?cle_emission="+integer)));


        //Traite chaque page et ajoute les informations traité à la BD
        htmlContent.forEach(e->addToDB(splitData(e)));


    }

    //Cette fonction va prendre le contenu d'une page HTML grâce au lien de celle ci.
    public static String getURLContent(String urlString){
        //Creation du contenu à retourner
        String content ="";

        try{
            //Etablissement de la connection
            URL url = new URL(urlString);
            //Lecture de la page
            BufferedReader reader = new BufferedReader(new InputStreamReader(url.openStream()));
            String line;
            while ((line = reader.readLine()) != null)
            {
                content+=line;
            }
            //Fermeture de la lecture
            reader.close();
        }catch (Exception e){
            return "";
        }

        return content;
    }

    //Cette fonction va parse la page pour trouver: le contenue, le titre, la date, le mp3
    public static String[] splitData(String htmlContent){
        String[] data = new String[5];


        //Date
        data[0] = htmlContent.substring(htmlContent.indexOf(") :<br />")+") :<br />".length(),htmlContent.length()-1);
        data[0]= data[0].substring(0,data[0].indexOf("<br />"));


        //Contenue
        data[1] = htmlContent.substring(htmlContent.indexOf("<div id=\"emission\">")+"<div id=\"emission\">".length(),htmlContent.length()-1);
        data[1]= data[1].substring(0,data[1].indexOf("</div>"));


        //MP3
        data[2] = htmlContent.substring(htmlContent.indexOf("<a href=\"../audio/")+"<a href=\"../audio/".length(),htmlContent.length()-1);

        if(data[2].length()>0){
            data[2]= data[2].substring(0,data[2].indexOf("\">"));

            data[2]="http://rendezvousavecmrx.free.fr/audio/"+data[2];

        }


        //Titre
        data[3] = htmlContent.substring(htmlContent.indexOf("<div id=\"titre\">")+"<div id=\"titre\">".length(),htmlContent.length()-1);
        data[3]= data[3].substring(0,data[3].indexOf("</div>"));



        data[4] = "root";




        return data;
    }

    //Ajout à la BD
    public static void addToDB(String[] args){

        try
        {
            Date dateL = null;
            java.sql.Date date = new java.sql.Date(Calendar.getInstance().getTime().getTime());
            try{
                dateL = formatter.parse(args[0]);
                date = new java.sql.Date(dateL.getTime());

            }catch (Exception e){
                // En cas d'erreur date garde sa valeur par défaut, c'est à dire au moment du lancement de la commande.
            }

            // Connection SQL
            Class.forName(myDriver);
            Connection conn = DriverManager.getConnection(myUrl, "sonettir", "dataviz");



            // Requête préparé
            String query = " INSERT INTO EventCrawler (date, description, mp3, nom, login)"
                    + " VALUES (?, ?, ?, ?, ?)";

            // Insertion des valeurs
            PreparedStatement preparedStmt = conn.prepareStatement(query);
            preparedStmt.setDate (1, date);
            preparedStmt.setString (2, args[1]);
            preparedStmt.setString   (3, args[2]);
            preparedStmt.setString(4, args[3]);
            preparedStmt.setString(5, args[4]);

            // Execute le requête
            preparedStmt.execute();

            conn.close();
        }
        catch (Exception e)
        {
            //Erreur de la connection SQL
            System.err.println("Got an exception!");
            System.err.println(e.getMessage());
        }
    }
}
