
$(document).ready(function () {
    var expanded = false;
    var user = [];
    var og_phobies = [];
    arr_phobies = [];
    var browser_id = null;
    chrome.storage.local.get('uniqueId', (result) => {
        browser_id = result.uniqueId;
        $.ajax({
            url: "http://localhost/sapli/sapli/sapli-api/user_api/dispatch.php",
            type: "GET",
            contentType: "application/json",
            data: {
                "browser_id": browser_id
            },
            success: function (data) {
                user = data["data"];
                $.ajax({
                    url: "http://localhost/sapli/sapli/sapli-api/phobia_api/dispatch.php",
                    type: "GET",
                    contentType: "application/json",
                    success: function (data) {
                        data["data"].forEach(element => {
                            $("#checkboxes").html($("#checkboxes").html() + "<label for='" + element['id_phobia'] + "'><input type='checkbox' id=" + element['id_phobia'] + " />" + element['name'] + "</label>");
                        });
                        $.ajax({
                            url: "http://localhost/sapli/sapli/sapli-api/user_phobia_api/dispatch.php",
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
            if (!arr_phobies.includes($(this).attr('id'))) {
                phobias.push($(this).attr('id'));
            }
        });
        $('#checkboxes input:not(:checked)').each(function () {
            if (arr_phobies.includes($(this).attr('id'))) {
                removed_phobias.push($(this).attr('id'));
            }
        });
        $.ajax({
            url: "http://localhost/sapli/sapli/sapli-api/user_phobia_api/dispatch.php",
            type: "DELETE",
            data: JSON.stringify({
                id_user: user.id_user,
                ids_phobias: removed_phobias
            }),
            success: function (data) {
                console.log(data);
            }
        });
        $.ajax({
            url: "http://localhost/sapli/sapli/sapli-api/user_phobia_api/dispatch.php",
            type: "POST",
            data: JSON.stringify({
                id_user: user.id_user,
                phobias: phobias
            }),
            success: function (data) {
                console.log(data);
            }
        });
    }
});