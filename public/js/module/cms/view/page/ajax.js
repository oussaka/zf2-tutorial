(function(){

	
    Page = can.Model({
        //permet d'effectuer un traitement sur les donnÃ©es brutes reÃ§ues du serveur
        //CanJS attend un tableau avec directement les donnÃ©es
        //Le controller une structure type [status=>"ok/error/...",message=>"",data=>[les donnÃ©es]]
        models : function(response){
            return can.Model.models.call(this,response.data);
        },
        findAll : 'GET /ZF2.local/zf2-tutorial/public/page/jsFindAll',
        create  : "POST /page/jsCreate",
        update  : "POST /page/jsUpdate",
        destroy : "POST /page/jsDestroy/{id}"
    },{});
    
    
	/*Page = can.Model({
        findAll : 'GET /page_rest',
        findOne : 'GET /page-rest/{id}',
        create : 'POST /page-rest',
        update : 'PUT /page_rest/{id}',
        destroy : 'DELETE /page_rest/{id}'
    },{});*/

    /**
     Â * Controller gÃ©nÃ©ral
     Â */
    PageController = can.Control({
        init : function() {
            this.element.html(can.view('page_list_EJS', {
                pages : this.options.pages
            }));
        },

        //Ã©coute l'Ã©vÃ¨nement crÃ©ation sur un objet de type page
        '{Page} created' : function(list, ev, page){
            //Ajoute la page crÃ©e Ã  la liste affichÃ©e
            this.options.pages.push(page);
        },

        //gÃ¨re le click sur les lien supprimer
        '.deletePage click': function(el, ev){
            //dÃ©truit le modÃ¨le ce qui va appeler POST /page/jsDestroy/{id}
            el.closest('.page').data('page').destroy();
        }
    });
    
    /**
     * Controller pour l'edition de page
     */
   EditPageController = can.Control({

       //Ouvre la fenêtre pour créer une nouvelle page
       openCreate: function(){
           this.options.page = new Page();
           this.element.html(can.view('page_create_EJS', {
               page: this.options.page
           }));
           this.element.find("a.button").button();
           this.element.dialog();
       },

       //Ouvre la fenêtre pour éditer une page
       open : function(page) {
           this.options.page = page
           this.element.html(can.view('page_edit_EJS', {
               page : page
           }));
           this.element.find("a.button").button();
           this.element.dialog();
       },

       //Ferme la fenêtre
       close: function(){
           this.element.dialog('close');
           this.element.find('.page_edit').remove();
       },

       //Contrôle et sauve la page
       savePage: function() {
           var form = this.element.find('form');
           var values = can.deparam(form.serialize());

           if(values.title !== "") {
               this.options.page.attr(values).save();
               this.close();
           }
       },

       //Ajoute l'évènement sur le bouton Ajouter une page
       '{document} #createPageButton click': function(){
           this.openCreate();
       },

       //Ajoute l'évènement sur le bouton Enregister
       '.save click' : function(el){
           this.savePage(el);
       },

       //Ajoute l'évènement sur le bouton Annuler
       '.cancel click' : function(){
           this.close();
       },

       //Ajoute l'évènement sur les liens Modifier
       '{document} .editPage click': function(el, ev){
           page = el.closest('.page').data('page');
           this.open(page);
       }
   });

    $(document).ready(function() {
        // $("a.button").button();

        //On attend la rÃ©cupÃ©ration des donnÃ©es pour instancier le controller
        $.when(Page.findAll()).then(function(pageResponse) {
            new PageController('#pages', {pages:pageResponse});
            new EditPageController('#editPageDialog',{page:null});
        });
    });

})()
