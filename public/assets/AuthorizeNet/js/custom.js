$(document).ready(function () {
    /********* On scroll heder Sticky *********/
    $(window).scroll(function () {
      var scroll = $(window).scrollTop();
      if (scroll >= 50) {
        $("header").addClass("head-sticky");
      } else {
        $("header").removeClass("head-sticky");
      }
    });
    window.onscroll = function () {
      var header = document.querySelector("header");
      if (window.pageYOffset > 0) {
        header.classList.add("head-sticky");
      } else {
        header.classList.remove("head-sticky");
      }
    };
    var prevScrollpos = window.pageYOffset;
    window.onscroll = function() {
      var currentScrollPos = window.pageYOffset;
      if (prevScrollpos > currentScrollPos) {
        document.getElementById("head-sticky").style.top = "0";
      } else {
        document.getElementById("head-sticky").style.top = "-200px";
      }
      prevScrollpos = currentScrollPos;
    }

    /*********  Multi-level accordion nav  ********/
    $('.acnav-label').click(function () {
      var label = $(this);
      var parent = label.parent('.has-children');
      var list = label.siblings('.acnav-list');
      if (parent.hasClass('is-open')) {
        list.slideUp('fast');
        parent.removeClass('is-open');
      }
      else {
        list.slideDown('fast');
        parent.addClass('is-open');
      }
    });
  
     /******  Nice Select  ******/
  $('select').niceSelect();

  document.addEventListener('DOMContentLoaded', function () {
    // Find the form and the card number input
    var form = document.getElementById('authorizenetForm'); // Replace 'yourFormId' with the actual ID of your form
    var cardNumberInput = form.querySelector('input[name="cardNumber"]');

    // Add an event listener to the form for the 'submit' event
    form.addEventListener('submit', function (event) {
        // Perform your custom validation
        if (!validateCardNumber(cardNumberInput.value)) {
            // Prevent the form submission if validation fails
            event.preventDefault();
            alert('Please enter a valid 16-digit card number.');
        }
    });

    // Custom validation function for card number
    function validateCardNumber(cardNumber) {
        var cardNumberRegex = /^\d{16}$/;
        return cardNumberRegex.test(cardNumber);
    }
});
  
});
