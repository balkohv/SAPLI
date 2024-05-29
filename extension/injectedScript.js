(function () {
    console.log('Injected script running');
    scriptElement = document.querySelector('script[src*="injectedScript.js"]');
    seekTime = parseInt(scriptElement.getAttribute('data-seek-time'), 10);
    seekVideo();
    function seekVideo() {
        try {
            let videoId = netflix.appContext.state.playerApp.getAPI().videoPlayer.getAllPlayerSessionIds()[0];
            netflix.appContext.state.playerApp.getAPI().videoPlayer.getVideoPlayerBySessionId(videoId).seek(seekTime);
            window.postMessage({ type: 'NETFLIX_CONTROL', message: 'Seeked to '+ seekTime }, '*');
        } catch (error) {
            console.error('Error seeking video:', error);
            window.postMessage({ type: 'NETFLIX_CONTROL', message: 'Error seeking video', error: error.toString() }, '*');
        }
    }
})();