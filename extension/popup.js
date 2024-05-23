$(document).ready(function () {

  if (chrome.storage.local.get('uniqueId', (result) => {
    console.log(result);
    if (result.uniqueId) {
      $.ajax({
        url: "http://localhost/sapli/sapli/sapli-api/user_api/dispatch.php",
        type: "GET",
        contentType: "application/json",
        data: JSON.stringify({
          browser_id: result.uniqueId
        }),
        success: function (data) {
          console.log(getTab());
          if (getTab() == "www.netflix.com") {
            window.location.href = "signal.html";
          } else {
            window.location.href = "home.html";
          }
        }
      });
      console.log('Unique ID already exists');
    }
  }));

  $("#register").click(function () {
    console.log("register");
    $("#register_form").show();
    $("#login_form").hide();
  });
  $("#login").click(function () {
    $("#register_form").hide();
    $("#login_form").show();
  });
  $("#submit_register").click(function () {
    var email = $("#email").val();
    var username = $("#username").val();
    var password = hashPassword($("#password").val());
    $.ajax({
      url: "http://localhost/sapli/sapli/sapli-api/user_api/dispatch.php",
      type: "POST",
      data: JSON.stringify({
        email: email,
        username: username,
        password: $("#password").val()
      }),
      success: function (data) {
        console.log(data);
        $.ajax({
          url: "http://localhost/sapli/sapli/sapli-api/user_api/dispatch.php",
          type: "POST",
          data: JSON.stringify({
            username: username,
            password: $("#password").val()
          }),
          success: function (data) {
            console.log(data);
            store_browser_id(data['data']);
          }
        });
      }
    });
  });
  $("#submit_login").click(function () {
    var username = $("#username_login").val();
    var password = hashPassword($("#password_login").val());
    $.ajax({
      url: "http://localhost/sapli/sapli/sapli-api/user_api/dispatch.php",
      type: "POST",
      contentType: "application/json",
      data: JSON.stringify({
        username: username,
        password: $("#password_login").val()
      }),
      success: function (data) {
        console.log(data);
        store_browser_id(data['data']);
      }
    });
  });
  async function hashPassword(password) {
    const encoder = new TextEncoder();
    const data = encoder.encode(password);
    const hashBuffer = await crypto.subtle.digest('SHA-256', data);
    const hashArray = Array.from(new Uint8Array(hashBuffer));
    const hashHex = hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
    return hashHex;
  }

  function store_browser_id(data) {
    chrome.storage.local.get('uniqueId', (result) => {
      if (!result.uniqueId || result.uniqueId != data) {
        chrome.storage.local.set({ uniqueId: data }, () => {
          console.log('Unique ID generated and stored');
          window.location.href = "home.html";
        });
      } else {
        console.log('Unique ID already exists');
      }
    });
  }

  async function getTab() {
    let queryOptions = { active: true, currentWindow: true };
    let tabs = await chrome.tabs.query(queryOptions);
    return tabs[0].url;
  }
});