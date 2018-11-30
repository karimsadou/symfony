$( function() {
    $( ".sortable" ).sortable();
    $( ".sortable" ).disableSelection();
} );

$( "#form" ).submit(function( event ) {
    var $table = $('#galform');
    var i = 0;
    $table.children('tr').each(function() {
        $(this).find('input:hidden').val(i);
        i++;
    });
});

$(document).ready(function() {

     initCollection('div#gallerybundle_gallery_images', '#galform', '#add_image');


    function initCollection(proto, table, add_bouton){
        // On récupère la balise <div> en question qui contient l'attribut « data-prototype » qui nous intéresse.
        var $proto = $(proto);
        //console.log($porto);
        var $table = $(table);
        //console.log($table);


        // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
        var index = $(table + ' tr').length;

        // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
        $(add_bouton).click(function(e) {
            addBloc($proto, $table, index);
            // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
            index++;

            e.preventDefault(); // évite qu'un # apparaisse dans l'URL
            return false;
        });

        // S'il existe déjà des catégories, on ajoute un lien de suppression pour chacune d'entre elles
        $table.children('tr').each(function() {
            addDeleteLink($(this));
        });
//                }

    }

    // La fonction qui ajoute un formulaire CategoryType
    function addBloc($proto, $table, index) {

        var template = $proto.attr('data-prototype')
            .replace(/__name__label__/g, 'Bloc n°' + (index+1))
            .replace(/__name__/g,        index);

        // On crée un objet jquery qui contient ce template
        var $prototype = $(template);

        // On ajoute au prototype un lien pour pouvoir supprimer la catégorie
        addDeleteLink($prototype);

        // On ajoute le prototype modifié à la fin de la balise <div>
        $table.append($prototype);

    }

    // La fonction qui ajoute un lien de suppression d'une label
    function addDeleteLink($prototype) {
        // Création du lien
        var $deleteLink = $('<td><a href="#" type="button" class="btn btn-danger">Supprimer</a></td>');

        // Ajout du lien
        $prototype.append($deleteLink);
        $prototype.append($('<div style="margin-right:50px;"></div>'));


        // Ajout du listener sur le clic du lien pour effectivement supprimer la catégorie
        $deleteLink.click(function(e) {
            $prototype.remove();

            e.preventDefault(); // évite qu'un # apparaisse dans l'URL
            return false;
        });
    }
    // prévisualiser image avant son téléchargement

    $(".row").change(function() {
        var img = this.parentNode.parentNode.childNodes[1].childNodes[1];
        var img1 = this.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.childNodes[1].childNodes[1].childNodes[3].childNodes[1];
        console.log(img1);
        //console.log(this.childNodes[3]);

        if (this.childNodes[3].firstElementChild.files[0] != undefined) {
            console.log(this.childNodes[3].firstElementChild.files[0]);

            var fReader = new FileReader();
            fReader.readAsDataURL(this.childNodes[3].firstElementChild.files[0]);
            fReader.onloadend = function (event) {
                $(img).attr('src', event.target.result);
                console.log(img);
                $(img).show();
                $(img1).attr('src', event.target.result);
                console.log(img1);
                $(img1).show();
            }
        }

    });



});