
const domain = window.location.hostname;
chrome.runtime.sendMessage({ type: "updateBadge", text: "on", color: "#4578c9" });
console.log("Domain of the site:", domain);
$(document).ready(async function () {
    var movie = null;
    var user = null;
    var currentTime = null;
    var duration = null;
    var title = null;
    var episode = null;

    switch (domain) {
        case "www.netflix.com":
            console.log("Netflix");
            load_info();
            function load_info() {
                console.log("Chargement des informations");
                var player = document.querySelector('video');
                if (player) {
                    title = document.querySelector('.default-ltr-cache-m1ta4i');
                    if (title != null) {
                        episode = title.querySelector('span').textContent;
                        title = title.querySelector('h4').textContent;
                    }
                    currentTime = player.currentTime;
                    duration = player.duration;
                    player.ontimeupdate = function () { myFunction() };
                }
                if (currentTime != "null" && title && duration != "null") {
                    info_loaded(title, episode, duration);
                } else {
                    sleep(500).then(() => {
                        load_info();
                    });
                }
            }



            break;
        case "www.disneyplus.com"://TODO: recuperer le time code
            console.log("Disney+");
            sleep(10000).then(() => {
                let title = document.querySelector('.title-field').textContent;
                let saison_episode = document.querySelector('.subtitle-field').textContent;
                let timecode = document.querySelector('.time-remaining-label').textContent;
                console.log(title, saison_episode, timecode);
            });
            break;
        case "www.primevideo.com":
            console.log("Amazon Prime Video");
            break;
        default:
            console.log("Site non pris en charge");
    }

    async function sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    function search_phobias() {
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
                        url: "http://localhost/sapli/sapli/sapli-api/user_phobia_api/dispatch.php",
                        type: "GET",
                        contentType: "application/json",
                        data: {
                            "id_user": user.id_user,
                            "id_movie": movie.id_movie
                        },
                        success: function (data) {
                            console.log(data);
                        }
                    });
                }
            });
        });
    }


    function info_loaded(title, episode, duration) {
        console.log("titre:", title, "episode:", episode, "duration:", duration);
        $.ajax({
            method: "GET",
            url: "http://localhost/sapli/sapli/sapli-api/movie_api/dispatch.php",
            contentType: "application/json",
            dataType: "json",
            data: {
                plateform: "Netflix",
                name: title,
                episode: episode,
                duration: duration
            },
            success: function (data) {
                console.log(data);
                movie = data["data"];
                search_phobias();
            },
            error: function (data) {
                console.log(data);
                if (data.status == 404) {
                    $.ajax({
                        method: "POST",
                        url: "http://localhost/sapli/sapli/sapli-api/movie_api/dispatch.php",
                        contentType: "application/json",
                        dataType: "json",
                        data: JSON.stringify({
                            plateform: "Netflix",
                            name: title,
                            episode: episode,
                            duration: duration
                        }),
                        success: function (data) {
                            console.log(data);
                            $.ajax({
                                method: "GET",
                                url: "http://localhost/sapli/sapli/sapli-api/movie_api/dispatch.php",
                                contentType: "application/json",
                                dataType: "json",
                                data: {
                                    plateform: "Netflix",
                                    name: title,
                                    episode: episode,
                                    duration: duration
                                },
                                success: function (data) {
                                    console.log(data);
                                    movie = data["data"];
                                    search_phobias();
                                }
                            });
                        },
                        error: function (data) {
                            console.log(data);
                        }
                    });
                }
            }
        });
    }

    function myFunction() {
        //console.log(player.currentTime);
        //player.seek(0);
    }
});