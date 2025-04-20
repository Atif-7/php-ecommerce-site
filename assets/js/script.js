// const navbarMenu = document.querySelector('nav-list');
// const navbarToggle = document.querySelector('.navbar-toggle');
// navbarToggle.addEventListener('click',()=>{
//     navbarMenu.classList.toggle('active')
// })

document.getElementById('hamburger').addEventListener('click', function() {
    document.querySelector('.nav-menu').classList.toggle('active');
});

document.getElementById('cross').addEventListener('click', function() {
    document.getElementById('alerts').innerHTML = "";
})

/* When the user clicks on the button, 
closes every dropdowns and open the only one passed as argument */

/* Javascript only */
function myFunction(element) {
    var dropdowns = document.getElementsByClassName("dd-menu");
    // element.nextSibling is the carriage return… The dropdown menu is the next next.
    var thisDropdown = element.nextSibling.nextSibling;
    if (!thisDropdown.classList.contains('dropdown-menu-open')) {  // Added to hide dropdown if clicking on the one already open
      var i;
      for (i = 0; i < dropdowns.length; i++) {
        dropdowns[i].classList.remove('dropdown-menu-open');
      }
    }
    // Toggle the dropdown on the element clicked
    thisDropdown.classList.toggle("dropdown-menu-open");

  /* W3Schools function to close the dropdown when clicked outside. */
    window.onclick = function(event) {
        if (!event.target.matches('.dd-btn')) {
          var dropdowns = document.getElementsByClassName("dd-menu");
          var i;
          for (i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('dropdown-menu-open')) {
                openDropdown.classList.remove('dropdown-menu-open');
                }
            }
        }
    }
}

// $(document).ready(function(){
//   $("#search-box").keyup(function(){
//       let query = $(this).val();
//       if (query.length > 1) {
//           $("#search-box").css("display","block")
//           $.ajax({
//               url: "search_suggestions.php",
//               method: "GET",
//               data: {query: query},
//               success: function(data) {
//                   console.log("AJAX Response:", data); // Debugging
//                   let results = JSON.parse(data);
//                   let suggestionBox = $("#suggestions");
//                   suggestionBox.empty();
//                   if (results.length > 0) {
//                       results.forEach(product => {
//                           suggestionBox.append(`<p><a href="product.php?id=${product.id}">${product.name}</a></p>`);
//                       });
//                   } else {
//                       suggestionBox.html("<p>No results found</p>");
//                   }
//               },
//               error: function(xhr, status, error) {
//                   console.log("AJAX Error:", status, error); // Debugging
//               }
//           });
//       } else {
//           $("#suggestions").empty();
//       }
//   });
// });

