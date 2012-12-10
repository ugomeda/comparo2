{include file="header.tpl"}
      <div id="header">
        <div id="logo">
          <a class="logo" href="index.php">Comparo<span>2 <span>(beta)</span></span></a>
          <a href="index.php" id="new-comparo">Comparer des sous-titres &raquo;</a>
        </div>
        <div id="navigation-vide">
        </div>
      </div>
      <div id="content" class="help">
        <h1>Aide</h1>
      
        <ul class="sommaire">
          <li><a href="#a-quoi-sert-comparo">À quoi sert Comparo² ?</a></li>
          <li><a href="#comparer-simplement">Comparer simplement des sous-titres</a></li>
          <li><a href="#lire-comparo">Lire un Comparo (utilisation basique)</a></li>
          <li><a href="#comparer-avance">Options avancées lors de la création d'un Comparo</a></li>
          <li><a href="#fonction-avancees">Fonctions avancées lors de la lecture d'un Comparo</a></li>
          <li><a href="#contact">Contact</a></li>
        </ul>
        
        <h2 id="a-quoi-sert-comparo">À quoi sert Comparo² ?</h2>
        <p>
          Comparo² vous permet de comparer simplement deux sous-titres. Les différences de synchronisation
          et les modifications du texte seront mises en évidence. Comparo² vous permet aussi d'annoter et
          de discuter des différences. Vous pouvez donc gagner du temps lors de la relecture de vos
          sous-titres et améliorer la qualité de ceux-ci en repérant les erreurs récurrentes.
        </p>
        <hr/>
        <h2 id="comparer-simplement">Comparer simplement des sous-titres</h2>
        <p>
          Il suffit de vous rendre sur <a href="index.php">la page d'accueil de Comparo²</a> et d'envoyer
          deux sous-titres via les champs approriés : le sous-titre avant relecture, et le sous-titre
          après relecture. Cliquez ensuite sur le bouton "Comparer", et c'est parti. Vous pourrez ensuite
          transmettre l'adresse du Comparo à d'autres personnes pour qu'ils puissent le lire.
        </p>
        <hr/>
        <h2 id="lire-comparo">Lire un Comparo (utilisation basique)</h2>
        <h3>Contenu</h3>
        <p>
          Un comparo se compose de lignes comme celle-ci :<br />
          <img src="img/help/line.png" width="545" height="97" alt="" class="center_img" />
          
          <strong>Icone</strong><br />
          Elle change suivant le type de modification apportée à la ligne :
        </p>
        <ul>
          <li><img src="img/tick.png" width="16" height="16" alt="" /> Aucune modification</li>
          <li><img src="img/time.png" width="16" height="16" alt="" /> Modification de synchronisation uniquement</li>
          <li><img src="img/pencil.png" width="16" height="16" alt="" /> Modification du texte uniquement</li>
          <li><img src="img/add.png" width="16" height="16" alt="" /> Ligne ajoutée</li>
          <li><img src="img/cancel.png" width="16" height="16" alt="" /> Ligne supprimée</li>
          <li><img src="img/clock_edit.png" width="16" height="16" alt="" /> Modification du texte et de la syncrhonisation</li>
        </ul>
        <p>
          <br />
          <strong>Timings</strong><br />
          Les timings sont rapellés à côté de l'icône. S'il y a eu une modification de synchronisation, le
          décalage est affiché.
          <br /><br />
          <strong>Version originale</strong><br />
          Si la version originale a été envoyée lors de la création du Comparo, celle-ci est affichée.
          <br /><br />
          <strong>Texte original et texte modifiés</strong><br />
          Le texte avec un fond rouge est le texte qui a été supprimé ou modifié. Le texte avec un fond vert
          est le texte ajouté.
        </p>
        <h3>Interface</h3>
        <p>
          En haut d'un Comparo se trouve cette barre :<br />
          <img src="img/help/barre.png" width="615" height="30" alt="" class="center_img" />
          <br />
          <strong>Détails</strong><br />
          Ce bouton ouvre l'onglet "Détails" dans lequel se trouvent les statistiques du Comparo et la liste
          des fichiers utilisés. Vous pouvez les télécharger en cliquant dessus.
          <br /><br />
          <strong>Filtres simples (icônes)</strong><br />
          Ces icônes sont les mêmes que celles affichées en face de chaque ligne du Comparo. En cliquant dessus
          vous pouvez choisir d'afficher ou de cacher un type de ligne. Par exemple, si vous êtes traducteur, il
          peut être utile de cacher les lignes n'ayant que des modifications de synchronisation en cliquant sur le
          bouton <img src="img/time.png" width="16" height="16" alt="" />.
          <br /><br />
          <strong>+ d'options</strong><br />
          Cet onglet sera utilisé dans le cadre d'une utilisation avancée de Comparo. Reportez-vous au chapitre "Utilisation avancée de Comparo".
        </p>
        <hr />
        
        <h2 id="comparer-avance">Options avancées lors de la création d'un Comparo</h2>
        <p>
          Lorsque vos choisissez les fichiers à Comparer, vous avez la possibilité d'ouvrir un menu "Options".
          <br /><br />
          <strong>Version originale</strong><br/>
          Vous pouvez uploader le sous-titre en version originale. La version originale sera affichée en plus des
          modifications de lignes.
          <br /><br />
          <strong>Fichier .scenechange</strong><br />
          Ce fichier, généré par VisualSubSync permet d'afficher les changements de plans dans les détails des timings.
          <br /><br />
          <strong>Encodage des caractères</strong><br />
          Si certains caractères accentués ne sont pas reconnus, c'est qu'il faut utiliser un autre encodage de caractères. Comparo
          supporte les charsets "Windows-1252" (Par défaut), "ISO-8859-15" et "UTF-8".
          <br /><br />
          <strong>Afficher les lignes non modifiées</strong><br />
          En activant cette option, les lignes qui n'ont subit aucune modification seront aussi affichées dans le Comparo.
          Cette fonction est désactivée par défaut pour accélérer le chargement des Comparos.
          <br /><br />
          <strong>Ignorer les tags</strong><br />
          Les tags seront supprimés des sous-titres.
        </p>
        <hr />
        <h2 id="fonction-avancees">Fonctions avancées lors de la lecture d'un Comparo</h2>
        <h3>Commenter un Comparo</h3>
        <p>
          Si vous êtes l'auteur d'un Comparo, vous pouvez le commenter simplement en cliquant sur la ligne
          à commenter. Un champ apparaît alors, vous permettant d'entrer le texte. Il suffit de valider en cliquant
          en dehors de la ligne ou en appuyant sur la touche Enter.<br />
          <img src="img/help/commentaire.png" width="330" height="151" alt="" class="center_img" />
        </p>
        <h3>Discuter sur un Comparo</h3>
        <p>
          En plus des commentaires, il est possible de discuter sur chaque ligne du Comparo. La différence est
          que tout le monde peut participer aux discussions, pas seulement l'auteur du Comparo. <br />
          Pour discuter, il suffit de cliquer sur l'icône <img src="img/user_comment.png" width="16" height="16" alt="" />
          qui s'affiche lorsque vous passez la souris sur une ligne.<br /><br />
          <img src="img/help/discuss.png" width="357" height="154" alt="" class="center_img" />
          <br />
          Pour éviter les abus, l'auteur du Comparo peut désactiver les discussions dans l'onglet "+ d'options".
          Il peut aussi supprimer les messages en cliquant sur la croix rouge qui s'affiche au passage de la souris.
        </p>
        <h3>Filtres avancés</h3>
        <p>
          En plus des filtres permettant d'afficher seulement certains types de modifications, deux filtres
          permettent de ne jamais cacher les lignes commentées ou discutées. Ils sont activés par défaut et se
          trouvent dans l'onglet "+ d'options".
        </p>
        <h3>Détail des timings</h3>
        <p>
          En plus de l'affichage succint des timings sur les lignes, il est possible d'avoir le détail des 
          timings en passant la souris dessus. Une fenêtre similaire s'affiche alors :<br /><br />
          <img src="img/help/detail_timings.png" width="381" height="157" alt="" class="center_img" />
          <br /><br />
          Les lignes en haut (en rouge) sont les timings et ID des lignes avant relecture, et en bas (vert) ceux relus.
          Le graphique présenté sur l'exemple ne s'affiche que si les timings sont différents. Il permet de mieux
          visualiser les différences entre les lignes. La barre noire verticale représente un changement de plan
          (si le fichier scenechange a été envoyé dans l'exemple).
        </p>
        <h3>Graphique</h3>
        <p>
          Dans le menu "+ d'option", il est possible d'activer le graphique. Celui-ci vous permet de visualiser
          les modifications dans le sous-titre. Le courbe rouge indique le nombre de lignes modifiées en fonction
          du temps tandis que la courbe grise indique le nombre de lignes en fonction du temps. Si vous ne vous
          intéressez qu'à une partie du Comparo, vous pouvez filtrer les lignes en sélectionnant une plage de
          timings sur le graphique.<br />
          <img src="img/help/graph.png" width="642" height="159" alt="" class="center_img" />
        </p>
        <h3>Copier une ligne</h3>
        <p>
          En passant la souris sur une ligne, l'icône <img src="img/style_edit.png" width="16" height="16" alt="" />
          s'affiche. Vous pouvez cliquer dessus pour afficher la ligne sous forme de texte, simple à copier-coller.
        </p>
        <h3>Changements de Reading Speed</h3>
        <p>
          Cette option de trouve dans l'onglet "+ d'options". Elle affichera les changements de Reading Speed. Comparo
          se base sur les algorithmes de VisualSubSync pour cette fonction.
        </p>
        <h3>Autres options d'affichage</h3>
        <p>
          D'autres options d'affichage se trouvent dans l'onglet "+ d'options", dans la rubrique "Autres options d'affichage".
          Nous ne les détaillerons pas ici car leur nom nous parraît suffisant comme explication.
        </p>
        <hr />
        <h2 id="contact">Contact</h2>
        <p>
          Si vous cherchez de l'aide, voulez proposer une amélioration ou signaler un bug, un topic spécial a été
          créé sur le forum de Subfactory : <a href="http://www.subfactory.fr/index.php?shard=forum&amp;action=g_reply&amp;ID=15981">Topic de Comparo²</a>.
        </p>
        <div style="height: 20px;"></div>
      </div>
{include file="footer.tpl"}
