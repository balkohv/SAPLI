
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
    var phobia_array = [];

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
                    player.pause();
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
            break;
    }
    var sapli_button = '<div id="sapli-container"><svg id="sapli-button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve"><path style="fill:#fcd559" d="M443.152 89.699H68.546c6.306-18.991 24.2-32.677 45.313-32.677 6.294 0 12.291 1.24 17.787 3.445C136.713 39.546 155.56 24 178.044 24c13.841 0 26.299 5.901 35.025 15.307 7.809-15.724 24.022-26.549 42.786-26.549 18.752 0 34.965 10.825 42.786 26.549C307.356 29.901 319.825 24 333.654 24c22.484 0 41.331 15.545 46.398 36.468a47.7 47.7 0 0 1 17.787-3.445c21.113-.001 39.007 13.685 45.313 32.676z"/><path style="fill:#fce195" d="M398.818 129.04H112.881c-21.127 0-22.1-31.781-.991-32.637a47.657 47.657 0 0 1 19.756 3.406c5.067-20.922 23.914-36.468 46.398-36.468 13.841 0 26.299 5.901 35.025 15.307 7.809-15.724 24.022-26.549 42.786-26.549 18.752 0 34.965 10.825 42.786 26.549 8.715-9.406 21.184-15.307 35.013-15.307 22.484 0 41.331 15.545 46.398 36.468a47.7 47.7 0 0 1 19.756-3.406c21.11.855 20.136 32.637-.99 32.637z"/><path style="fill:#f7729b" d="m436.798 123.222-2.205 30.221-23.458 321.742c-1.131 15.512-14.046 27.519-29.598 27.519H130.173c-15.553 0-28.467-12.007-29.598-27.518L77.106 153.443 74.9 123.222h361.898z"/><path style="fill:#e55c8d" d="m185.686 475.186-23.468-321.743-2.205-30.221H74.9l2.205 30.221 23.468 321.743c1.131 15.511 14.046 27.518 29.598 27.518h85.112c-15.551 0-28.466-12.008-29.597-27.518z"/><path style="fill:#f2f2f2" d="m384.022 123.222-1.562 30.221-18.037 349.261h-67.189l6.879-349.261.596-30.221z"/><path style="fill:#f7729b" d="m304.709 123.222-.596 30.221-6.879 349.261h-82.77l-6.879-349.261-.596-30.221z"/><path style="fill:#f2f2f2" d="M214.464 502.68v.024h-67.177l-18.049-349.261-1.562-30.221h79.313l.596 30.221z"/><path style="fill:#ccc" d="M215.287 502.704h-68l-18.049-349.261-1.562-30.221h32.331l2.205 30.221 23.473 321.746c1.109 15.236 13.59 27.085 28.778 27.491.275.024.549.024.824.024z"/><path style="fill:#f7729b" d="M469.463 105.197v1.931c0 9.871-7.999 17.882-17.882 17.882H60.118c-9.883 0-17.882-8.011-17.882-17.882v-1.931c0-9.883 7.999-17.882 17.882-17.882H451.58c9.883 0 17.883 7.999 17.883 17.882z"/><path d="M451.731 76.941h-2.38a56.592 56.592 0 0 0-51.362-32.677c-4.078 0-8.115.436-12.073 1.303-8.785-20.601-29.058-34.325-52.112-34.325-11.898 0-23.47 3.793-33.033 10.646C290.158 8.17 273.827 0 256.005 0c-17.832 0-34.164 8.171-44.772 21.888-9.569-6.852-21.144-10.647-33.039-10.647-23.054 0-43.326 13.723-52.111 34.324a56.416 56.416 0 0 0-12.073-1.301 56.587 56.587 0 0 0-51.361 32.677h-2.381c-14.791 0-26.823 12.032-26.823 26.823v1.931c0 14.791 12.032 26.823 26.823 26.823h6.47L93.816 503.71a8.94 8.94 0 0 0 8.917 8.29H409.28a8.941 8.941 0 0 0 8.917-8.291l27.065-371.192h6.469c14.79 0 26.823-12.032 26.823-26.823v-1.931c0-14.79-12.034-26.822-26.823-26.822zM84.668 132.518h34.669l18.687 361.6h-26.978l-26.378-361.6zm113.705 0 4.46 226.435a8.942 8.942 0 0 0 8.936 8.766l.18-.001a8.941 8.941 0 0 0 8.763-9.115l-4.454-226.083h79.484l-7.124 361.6h-65.236l-2.011-102.126a8.942 8.942 0 0 0-9.115-8.765 8.94 8.94 0 0 0-8.763 9.115l2.004 101.774H155.93l-18.687-361.6h61.13zm202.594 361.6h-26.978l11.899-230.401a8.94 8.94 0 0 0-8.469-9.39c-4.956-.263-9.135 3.537-9.391 8.469l-11.946 231.323h-49.579l7.122-361.6h61.132l-5.015 97.109a8.94 8.94 0 0 0 8.939 9.402 8.94 8.94 0 0 0 8.921-8.48l5.063-98.031h34.669l-26.367 361.599zm59.705-388.423c0 4.931-4.01 8.941-8.941 8.941H60.268c-4.931 0-8.941-4.012-8.941-8.941v-1.931c0-4.929 4.01-8.941 8.941-8.941h294.089c4.939 0 8.941-4.003 8.941-8.941s-4.002-8.941-8.941-8.941H83.535a38.736 38.736 0 0 1 30.476-14.794c4.959 0 9.823.943 14.457 2.802a8.94 8.94 0 0 0 12.021-6.193c4.225-17.446 19.731-29.631 37.709-29.631 10.913 0 21.023 4.42 28.471 12.447a8.941 8.941 0 0 0 14.564-2.104c6.608-13.314 19.935-21.585 34.775-21.585 14.83 0 28.157 8.272 34.779 21.588a8.944 8.944 0 0 0 6.732 4.868 8.934 8.934 0 0 0 7.831-2.773c7.327-7.907 17.7-12.444 28.455-12.444 17.977 0 33.484 12.185 37.709 29.631a8.943 8.943 0 0 0 12.013 6.195 38.75 38.75 0 0 1 14.464-2.805c12.16 0 23.269 5.64 30.476 14.794h-42.516a8.94 8.94 0 0 0-8.941 8.941 8.94 8.94 0 0 0 8.941 8.941h65.782c4.931 0 8.941 4.01 8.941 8.941v1.935z"/></svg></div>'
    var body = document.querySelector('body');
    body.addEventListener("mousemove", function () {
        if (!document.getElementById('sapli-button')) {
            $(".default-ltr-cache-m1ta4i").append(sapli_button);
            //$("#appMountPoint").append(sapli_button);
            $("#sapli-button").click(function () {
                add_warning_overlay();
            });
        }
    });


    $.ajax({
        url: "http://localhost/sapli/sapli/sapli-api/phobia_api/dispatch.php",
        type: "GET",
        contentType: "application/json",
        success: function (data) {
            console.log(data);
            phobia_array = data["data"];
        }

    });

    // $("head").append('<link rel="stylesheet" type="text/css" href="https://93.8.28.18:82/modal.css">');
    //$("body").append('<div class="modal-pw"><div class="content-container"></div></div>'); 
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

    function add_warning_overlay() {
        //seconde to minute
        if (document.getElementById("overlay")) {
            document.getElementById("overlay").remove();
            document.getElementById("sapli-container").style.marginTop = "0px";
            return;
        }
        var record1 ='<svg id="pin1" style="height: 25px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64"><path style="fill:#c73939" d="M32 5a21 21 0 0 0-21 21c0 17 21 33 21 33s21-16 21-33A21 21 0 0 0 32 5zm0 31a10 10 0 1 1 10-10 10 10 0 0 1-10 10z"/></svg>';
        var record2 ='<svg id="pin2" style="height: 25px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64"><path style="fill:#c73939" d="M32 5a21 21 0 0 0-21 21c0 17 21 33 21 33s21-16 21-33A21 21 0 0 0 32 5zm0 31a10 10 0 1 1 10-10 10 10 0 0 1-10 10z"/></svg>';
        var time = duration / 60;
        var minutes = Math.floor(time);
        var seconds = Math.floor((time - minutes) * 60);
        console.log(minutes, seconds);
        var currentTimeMinutes = Math.floor(currentTime / 60);
        var currentTimeSeconds = Math.floor((currentTime - currentTimeMinutes) * 60);
        var container = document.getElementById("sapli-container");
        container.style.marginTop = "-165px";
        var overlay = document.createElement("div");
        var select = document.createElement('select');
        select.id = "phobias_select";
        var option_d = document.createElement('option');
        option_d.value = "Selectionnez une phobie";
        option_d.text = "Selectionnez une phobie";
        option_d.disabled = true;
        option_d.selected = true;
        var input_container = document.createElement('div');
        input_container.id = "input_container";
        var input_start = document.createElement('input');
        input_start.type = "time";
        input_start.className = "time_input";
        input_start.id = "start_time";
        input_start.min = "00:00";
        input_start.max = minutes + ":" + seconds;
        var input_end = document.createElement('input');
        input_end.type = "time";
        input_end.className = "time_input";
        input_end.id = "end_time";
        input_end.min = "00:00";
        input_end.max = minutes + ":" + seconds;
        input_end.id = "end_time";
        overlay.className = "overlay";
        overlay.id = "overlay";
        var submit = document.createElement('button');
        submit.id = "submit";
        submit.textContent = "Signaler";
        container.appendChild(overlay);
        overlay.appendChild(select);
        select.appendChild(option_d);
        for (var i = 0; i < phobia_array.length; i++) {
            var option = document.createElement('option');
            option.value = phobia_array[i].id_phobia;
            option.text = phobia_array[i].name;
            select.appendChild(option);
        }
        overlay.appendChild(input_container);
        $("#input_container").append(record1);
        input_container.appendChild(input_start);
        input_container.appendChild(input_end);
        $("#input_container").append(record2);
        overlay.appendChild(submit);

        $("#start_time").val(currentTimeMinutes + ":" + currentTimeSeconds);
        $("#end_time").val(currentTimeMinutes + ":" + currentTimeSeconds);
        $("#time_start").change(function () {
            $("#time_end").attr("min", $("#time_start").val());
        });
    }
});