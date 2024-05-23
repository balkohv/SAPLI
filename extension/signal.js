$.document.ready(function () {
    $.ajax({
        url: "http://localhost/sapli/sapli/sapli-api/phobia_api/dispatch.php",
        type: "GET",
        contentType: "application/json",
        success: function (data) {
            data["data"].forEach(element => {
                $("#select-phobie").html($("#select-phobie").html() + "<option value='" + element['id_phobia'] + "'>" + element['name'] + "</option>");
            });
        }
    });
});