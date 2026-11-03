$(function () {
  $("#registrationForm").on("submit", function (event) {
    let valid = true;
    $(".error").text("");

    const name = $("#full_name").val().trim();
    const email = $("#email").val().trim();
    const phone = $("#phone").val().trim();
    const course = $("#course").val();
    const agree = $("#agree").is(":checked");

    if (!name) {
      $("#full_name").siblings(".error").text("Please enter your full name.");
      valid = false;
    }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      $("#email").siblings(".error").text("Please enter a valid email.");
      valid = false;
    }
    if (!/^[0-9+().\-\s]{7,20}$/.test(phone)) {
      $("#phone").siblings(".error").text("Please enter a valid phone number.");
      valid = false;
    }
    if (!course) {
      $("#course").siblings(".error").text("Please select an application type.");
      valid = false;
    }
    if (!agree) {
      $(".consent-error").text("Please confirm before submitting.");
      valid = false;
    }

    if (!valid) {
      event.preventDefault();
      $("html, body").animate({ scrollTop: $(".form-card").offset().top - 15 }, 250);
    }
  });
});
