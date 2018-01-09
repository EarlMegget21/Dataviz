<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $pagetitle; ?></title>

        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="./css/style.css">

        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <?php
            if (isset($affiche)) {
                $filepath = File::build_path(array("view", "event", "ScriptMap.php"));
                require $filepath;
            }
        ?>
    </head>
    <body>
        <header>
            <div id="metanav" class="navbar-metanav" data-no-instant=""><ul class="level1"><li class="handle"><span data-metanav-handle="" class="icon-iconeradiofrance"></span></li><li><a target="_blank" title="Radio France" href="http://www.radiofrance.fr" sl-processed="1">
                            Radio France</a></li><li><a target="_blank" title="France Inter" href="https://www.franceinter.fr" sl-processed="1">
                            France Inter</a></li><li><a target="_blank" title="franceinfo" href="http://www.francetvinfo.fr" sl-processed="1">
                            franceinfo</a></li><li><a target="_blank" title="France Bleu" href="https://www.francebleu.fr" sl-processed="1">
                            France Bleu</a></li><li><a target="_blank" title="France Culture" href="https://www.franceculture.fr" sl-processed="1">
                            France Culture</a></li><li><a target="_blank" title="France Musique" href="https://www.francemusique.fr" sl-processed="1">
                            France Musique</a></li><li><a target="_blank" title="Fip" href="http://www.fipradio.fr/" sl-processed="1">
                            Fip</a></li><li><a target="_blank" title="Mouv" href="http://www.mouv.fr" sl-processed="1">
                            Mouv</a></li><li data-metanav-submenu="" class="submenu"><button type="button" data-metanav-submenu-handle=""><span class="icon-more">+</span></button><ul class="level2"><li><a target="_blank" title="Un Monde de Radio France" href="https://monde.radiofrance.fr" sl-processed="1">
                                    Un Monde de Radio France</a></li><li><a target="_blank" title="le Médiateur" href="http://mediateur.radiofrance.fr" sl-processed="1">
                                    le Médiateur</a></li><li><a target="_blank" title="les Éditions" href="http://editions.radiofrance.fr" sl-processed="1">
                                    les Éditions</a></li><li><a target="_blank" title="Maison de la Radio" href="http://www.maisondelaradio.fr" sl-processed="1">
                                    Maison de la Radio</a></li></ul></li></ul></div>
            <nav class="menu">
                <a href="index.php?controller=event">Event</a>
                <?php
                    if (!isset($_SESSION["login"])) {
                        echo "<a href='index.php?action=connect&controller=utilisateurs'>Connect</a>";
                    } else {
                        if ($_SESSION["isAdmin"] == 1) {
                            echo "<a href=\"index.php?controller=utilisateurs\">Utilisateurs</a>";
                        }
                        echo "<a href='index.php?controller=utilisateurs&action=read&login=" . $_SESSION["login"] . "'>Mon Profil</a>";
                        echo "<a href='index.php?action=disconnect&controller=utilisateurs'>Disconnect</a>";
                    }
                ?>
            </nav>
        </header>
        <div id='main'>
        <?php
            $filepath = File::build_path(array("view", $object, "$view.php"));
            require $filepath;
        ?>
        </div>
        <footer class="footer">
            <script data-no-instant="">
                (function(window, doc) {
                    var metanavSubmenu = doc.querySelector('[data-metanav-submenu]');
                    var metanavSubmenuHandle = doc.querySelector('[data-metanav-submenu-handle]');
                    var metanavHandle = doc.querySelector('[data-metanav-handle]');
                    var metanav = doc.querySelector('#metanav');
                    var metanavLink = doc.querySelector('#metanav a');

                    // /*-----------
                    //   metanav functions
                    // -----------*/

                    // closing the metanav submenu
                    function closeMetanav() {
                        metanav.classList.remove('open');
        //                    $("[data-metanav-submenu]").removeClass("open");
                    }

                    //TODO Add windows resize
        //                window.resize(function(){
        //                    closeMetanav();
        //                });

                    // /*----------
                    //   triggers
                    // ----------*/

                    // switches menus
        //                metanavSubmenuHandle.
                    metanavSubmenuHandle.addEventListener('click', function() {
                        metanavSubmenu.classList.toggle('open');
        //                    $("[data-metanav-submenu]").toggleClass("open");
                    });
        //                $("[data-metanav-submenu-handle]").click(function() {
        //                    metanavSubmenu.classList.toggle('open');
        ////                    $("[data-metanav-submenu]").toggleClass("open");
        //                });
                    metanavHandle.addEventListener('click', function() {
        //                $("[data-metanav-handle]").click(function() {
                        metanav.classList.toggle('open');
        //                    $("#metanav").toggleClass("open");
                    });

                    metanavLink.addEventListener('click', function() {
        //                $("#metanav a").click(function() {
                        closeMetanav();
                    });

                    metanav.addEventListener('mouseleave', function() {
                        closeMetanav();
                    });

                })(window, document);
            </script>
            <div class="container"><header><svg class="icon footer-icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_france-inter-filet"></use></svg></header><div class="wrapper"><div class="thematic-bloc"><div class="thematic-bloc-title"><span>Écouter</span></div><ul class="thematic-bloc-content-list"><li class="thematic-bloc-content-list-item"><a href="/direct" sl-processed="1">Direct vidéo</a></li><li class="thematic-bloc-content-list-item"><a href="/videos" sl-processed="1">Portail vidéo</a></li><li class="thematic-bloc-content-list-item"><a href="#" data-is-live="1" class="playable" sl-processed="1">Direct audio</a></li><li class="thematic-bloc-content-list-item"><a href="/programmes" sl-processed="1">Grille des programmes</a></li><li class="thematic-bloc-content-list-item"><a href="/replay" sl-processed="1">Émissions en replay</a></li><li class="thematic-bloc-content-list-item"><a href="/frequences" sl-processed="1">Fréquences</a></li><li class="thematic-bloc-content-list-item"><a href="http://www.radiofrance.fr/boite-a-outils/faq" target="_blank" sl-processed="1">Aide à l'écoute</a></li></ul></div><div class="thematic-bloc"><div class="thematic-bloc-title"><span>Thématiques</span></div><ul class="thematic-bloc-content-list"><li class="thematic-bloc-content-list-item-meta"><a href="/info" sl-processed="1"><strong>Info</strong></a><p><a href="/info" sl-processed="1">Info</a><a href="/politique" sl-processed="1">Politique</a><a href="/societe" sl-processed="1">Société</a><a href="/justice" sl-processed="1">Justice</a><a href="/economie" sl-processed="1">Économie</a><a href="/monde" sl-processed="1">Monde</a><a href="/sports" sl-processed="1">Sports</a></p></li><li class="thematic-bloc-content-list-item-meta"><a href="/culture" sl-processed="1"><strong>Culture</strong></a><p><a href="/culture" sl-processed="1">Culture</a><a href="/cinema" sl-processed="1">Cinéma</a><a href="/theatre" sl-processed="1">Théâtre</a><a href="/livres" sl-processed="1">Livres</a><a href="/histoire" sl-processed="1">Histoire</a><a href="/idees" sl-processed="1">Idées</a><a href="/sciences" sl-processed="1">Sciences</a></p></li><li class="thematic-bloc-content-list-item-meta"><a href="/humour" sl-processed="1"><strong>Humour</strong></a></li><li class="thematic-bloc-content-list-item-meta"><a href="/musique" sl-processed="1"><strong>Musique</strong></a></li></ul></div></div><div class="wrapper"><div class="thematic-bloc"><div class="thematic-bloc-title"><span>Abonnez-vous</span></div><ul class="thematic-bloc-content-list"><li class="thematic-bloc-content-list-item"><a href="/rss/a-la-une.xml" sl-processed="1">Flux Rss</a></li><li class="thematic-bloc-content-list-item"><a href="/culture/avec-l-application-france-inter-retrouvez-gratuitement-tout-l-univers-de-votre-radio" sl-processed="1">Application mobile</a></li><li class="thematic-bloc-content-list-item"><a href="/newsletter" sl-processed="1">Newsletter</a></li></ul></div></div><div class="wrapper"><div class="thematic-bloc"><div class="thematic-bloc-title"><span>France Inter</span></div><ul class="thematic-bloc-content-list"><li class="thematic-bloc-content-list-item-meta"><ul><li><a href="http://www.dailymotion.com/franceinter" data-share="Dailymotion" class="icon icon-dailymotion share" rel="nofollow" target="_blank" sl-processed="1"><svg class="icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_follow-dailymotion"></use></svg></a></li><li><a href="https://twitter.com/franceinter" data-share="Twitter" class="icon icon-twitter share" rel="nofollow" target="_blank" sl-processed="1"><svg class="icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_follow-twitter"></use></svg></a></li><li><a href="https://www.facebook.com/franceinter" data-share="Facebook" class="icon icon-facebook share" rel="nofollow" target="_blank" sl-processed="1"><svg class="icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_follow-facebook"></use></svg></a></li></ul></li><li class="thematic-bloc-content-list-item"><a href="/contact" sl-processed="1">Contact</a></li><li class="thematic-bloc-content-list-item"><a href="/espace-presse" sl-processed="1">Espace presse</a></li><li class="thematic-bloc-content-list-item"><a href="/partenariats" sl-processed="1">Partenariats</a></li><li class="thematic-bloc-content-list-item"><a href="/meteo-marine" sl-processed="1">Météo marine</a></li></ul></div><div class="thematic-bloc"><div class="thematic-bloc-title"><span>Index</span></div><ul class="thematic-bloc-content-list"><li class="thematic-bloc-content-list-item"><a href="/archives" sl-processed="1">Archives</a></li><li class="thematic-bloc-content-list-item"><a href="/emissions" sl-processed="1">Toutes les émissions</a></li></ul></div></div></div><footer><div class="container"><a href="http://editions.radiofrance.fr/" sl-processed="1">Editions</a><a href="http://espacepublic.radiofrance.fr/" sl-processed="1">médiateur</a><a href="http://www.radiofrance.fr/mentions-legales/" sl-processed="1">mentions légales</a><a href="http://www.radiofrancepublicite.fr/" sl-processed="1">annonceurs</a><a href="/frequences" sl-processed="1">fréquences</a><a href="http://www.ojd.com/Marque/marque-franceinter" rel="nofollow" sl-processed="1">OJD</a></div></footer></footer>
    </body>
</html>

