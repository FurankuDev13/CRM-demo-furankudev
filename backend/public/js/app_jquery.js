var callAjax = {
  init: function() {
    $(".showModal").click(function(event) {
        event.preventDefault();
        $(this).siblings(".modal").addClass("is-active");  
      });
      
    $(".modal-close").click(function(event) {
      $(this).parents(".modal").removeClass("is-active");
    });

    $(".showModalAttachment").click(function(event) {
      event.preventDefault();
      $(this).siblings(".modalAttachment").addClass("is-active");  
    });
    
    $(".modal-close").click(function(event) {
      $(this).parents(".modal").removeClass("is-active");
    });

    // pour les Modals de Confirmation / Annulation
    $(".showModalConfirm").click(function(event) {
        event.preventDefault();
        $(this).siblings(".modal").addClass("is-active");  
      });
      
    $(".modal-closeConfirm").click(function(event) {
      event.preventDefault();
      $(this).parents(".modal").removeClass("is-active");
    });
  }
}

$(callAjax.init());