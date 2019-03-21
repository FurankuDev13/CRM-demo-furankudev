var callAjax = {
  init: function() {
    $(".showModal").click(function(event) {
        $(this).siblings(".modal").addClass("is-active");  
      });
      
      $(".modal-close").click(function(event) {
        $(this).parents(".modal").removeClass("is-active");
    });
  }
}

$(callAjax.init());