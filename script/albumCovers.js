const playlistItemsPic = document.querySelectorAll('ul li');
const albumCover = document.getElementById('album-cover');

playlistItemsPic.forEach(item => {
    item.addEventListener('click', () => {
        const newAlbumCover = item.getAttribute('data-album');
        
        albumCover.src = newAlbumCover;
    });
});
