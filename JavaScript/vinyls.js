// Builds a wrap-around cover carousel from window.ALBUMS (defined in albums.js).
// Only the active album and the 3 either side are visible, only the active
// album's text is shown, and the Spotify player follows the active album.

const albums = window.ALBUMS;
const total = albums.length;
let activeIndex = Math.min(36, total - 1);   // index 3 = Hypersonic Missiles

const stack = document.getElementById("cover-stack");
const titleEl = document.getElementById("album-title");
const yearEl = document.getElementById("album-year");
const genreEl = document.getElementById("album-genre");
const leftBtn = document.getElementById("prev-btn");    // the "<" button
const rightBtn = document.getElementById("next-btn");   // the ">" button
const playerWrap = document.getElementById("album-player");
const playerSlot = document.getElementById("spotify-player");

const PLACES = ["", "one", "two", "three"];

// How far album i is from the active one, wrapping around the ends of the list.
// With 7 albums this gives -3 to +3, so every album has a slot.
// With more, the extra albums land beyond +/-3 and wait out of sight.
function offsetFrom(i) {
    const half = Math.floor(total / 2);
    return ((((i - activeIndex + half) % total) + total) % total) - half;
}

// Turns an offset into the matching CSS class
function positionClass(offset) {
    if (offset === 0) return "front";
    if (offset > 3) return "far-next";
    if (offset < -3) return "far-prev";

    const side = offset > 0 ? "next" : "prev";
    return `${side}-${PLACES[Math.abs(offset)]}`;
}

// One <img> per album, created once
const covers = albums.map((album, i) => {
    const img = document.createElement("img");
    img.src = album.cover;
    img.alt = `${album.title} by ${album.artist}`;
    img.addEventListener("click", () => setActive(i));
    stack.appendChild(img);
    return img;
});


// === Spotify player ===
// Uses Spotify's embed iFrame API, so there is no API key or login on your side.
// Visitors get a short preview, and full playback if they are signed in to Spotify.

let embedController = null;   // set once Spotify's script has loaded
let shownUri = null;          // the album currently loaded in the player
let loadTimer = null;

function spotifyUri(album) {
    return `spotify:album:${album.spotify}`;
}

// Points the player at the active album (hides it if the album has no Spotify ID)
function updatePlayer() {
    const album = albums[activeIndex];
    playerWrap.hidden = !album.spotify;
    if (!album.spotify || !embedController) return;

    const uri = spotifyUri(album);
    clearTimeout(loadTimer);
    if (uri === shownUri) return;

    // Short delay so clicking through albums quickly only loads the one you stop on
    loadTimer = setTimeout(() => {
        embedController.loadEntity(uri);
        shownUri = uri;
    }, 300);
}

// Spotify's script calls this once it is ready
window.onSpotifyIframeApiReady = (IFrameAPI) => {
    const start = albums[activeIndex].spotify
        ? albums[activeIndex]
        : albums.find((album) => album.spotify);
    if (!start) return;

    IFrameAPI.createController(
        playerSlot,
        { uri: spotifyUri(start), width: "100%", height: "152" },
        (controller) => {
            embedController = controller;
            shownUri = spotifyUri(start);
            updatePlayer();   // in case the album changed while Spotify was loading
        }
    );
};

const spotifyScript = document.createElement("script");
spotifyScript.src = "https://open.spotify.com/embed/iframe-api/v1";
spotifyScript.async = true;
document.body.appendChild(spotifyScript);


// === Carousel ===

function render() {
    covers.forEach((img, i) => {
        img.className = positionClass(offsetFrom(i));
    });

    const album = albums[activeIndex];
    titleEl.textContent = `${album.title} - ${album.artist}`;
    yearEl.textContent = `Released: ${album.year}`;
    genreEl.textContent = `Genre: ${album.genre}`;

    // The list wraps, so the arrows only switch off if there is nothing to move to
    leftBtn.disabled = rightBtn.disabled = total < 2;

    updatePlayer();
}

function setActive(index) {
    activeIndex = ((index % total) + total) % total;   // wraps past either end
    render();
}

// ">" slides the covers to the right, "<" slides them to the left
rightBtn.addEventListener("click", () => setActive(activeIndex - 1));
leftBtn.addEventListener("click", () => setActive(activeIndex + 1));

render();