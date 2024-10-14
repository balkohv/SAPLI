
$(document).ready(function () {
    var expanded = false;
    var user = [];
    var og_phobies = [];
    arr_phobies = [];
    var browser_id = null;
    token = null;
    chrome.storage.local.get('auto', (result) => {
        if (result.auto != null) {
            $("#auto" + result.auto).prop('checked', true);
        } else {
            $("#rien").prop('checked', true);
        }
    });

    chrome.storage.local.get('uniqueId', (result) => {
        browser_id = result.uniqueId;
        $.ajax({
            url: "https://phobia-warning.com/sapli-auth/",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({
                "login": "test", //FIXME: changer les identifiants
                "mdp": "test"
            }),
            success: function (data) {
                token = data["data"];
                $.ajax({
                    url: "https://phobia-warning.com/sapli-api/user_api/",
                    headers: {
                        Authorization: 'Bearer ' + token
                    },
                    type: "GET",
                    contentType: "application/json",
                    data: {
                        "browser_id": browser_id
                    },
                    success: function (data) {
                        user = data["data"];
                        $.ajax({
                            url: "https://phobia-warning.com/sapli-api/phobia_api/",
                            headers: {
                                Authorization: 'Bearer ' + token
                            },
                            type: "GET",
                            contentType: "application/json",
                            success: function (data) {
                                data["data"].forEach(element => {
                                    $("#checkboxes").html($("#checkboxes").html() + "<label for='" + element['id_phobia'] + "'><input type='checkbox' id=" + element['id_phobia'] + " />" + element['name'] + "</label>");
                                });
                                $.ajax({
                                    url: "https://phobia-warning.com/sapli-api/user_phobia_api/",
                                    headers: {
                                        Authorization: 'Bearer ' + token
                                    },
                                    type: "GET",
                                    contentType: "application/json",
                                    data: {
                                        "id_user": user.id_user
                                    },
                                    success: function (data) {
                                        og_phobies = data["data"];
                                        for (var i = 0; i < Object.keys(data["data"]).length; i++) {
                                            $("#" + og_phobies[i]["id_phobia"]).prop('checked', true);
                                            arr_phobies.push(og_phobies[i]["id_phobia"]);
                                        }
                                    }
                                });
                            }
                        });
                    }
                });
            }
        });

    });
    $("#logout").click(function () {
        chrome.storage.local.remove('uniqueId', function () {
            window.location.href = "pop_up.html";
        });
    });
    $('#showCheckboxes').click(showCheckboxes);

    function showCheckboxes() {
        var checkboxes = document.getElementById("checkboxes");
        var select = document.querySelector("select");
        if (!expanded) {
            checkboxes.style.display = "block";
            select.style.borderRadius = "6px 6px 0 0";
            expanded = true;
        } else {
            checkboxes.style.display = "none";
            select.style.borderRadius = "6px";
            expanded = false;
            update_phobies();
        }
    }

    function update_phobies() {
        var phobias = [];
        var removed_phobias = [];
        $('#checkboxes input:checked').each(function () {
            phobias.push($(this).attr('id'));
        });
        $('#checkboxes input:not(:checked)').each(function () {
            removed_phobias.push($(this).attr('id'));
        }); $.ajax({
            url: "https://phobia-warning.com/sapli-auth/",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({
                "login": "test", //FIXME: changer les identifiants
                "mdp": "test"
            }),
            success: function (data) {
                token = data["data"];
                $.ajax({
                    url: "https://phobia-warning.com/sapli-api/user_phobia_api/",
                    headers: {
                        Authorization: 'Bearer ' + token
                    },
                    type: "DELETE",
                    data: JSON.stringify({
                        id_user: user.id_user,
                        ids_phobias: removed_phobias
                    }),
                    success: function (data) {
                        console.log(data);
                        removed_phobias = [];
                    }
                });
                $.ajax({
                    url: "https://phobia-warning.com/sapli-api/user_phobia_api/",
                    headers: {
                        Authorization: 'Bearer ' + token
                    },
                    type: "POST",
                    data: JSON.stringify({
                        id_user: user.id_user,
                        phobias: phobias
                    }),
                    success: function (data) {
                        console.log(data);
                        phobias = [];
                    }
                });
            }
        });

    }

    $("input[type='radio']").click(function () {
        chrome.storage.local.set({ auto: $("input[type='radio']:checked").val() });
    });
});