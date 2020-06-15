let watchListIcons = document.querySelectorAll('.watchlist');

for (var i = 0; i < watchListIcons.length; i++) {
    watchListIcons[i].addEventListener('click', addToWatchlist);
}


function addToWatchlist(event) {

    let watchlistIcon = event.target;
    let link = watchlistIcon.dataset.href;

    fetch(link).then(function (res) {
        if (res.ok) {
            console.log("Dev: Ajax reponse 200");
            res.json().then(function (data) {
                if(data.isInWatchlist){
                    watchlistIcon.classList.add('fas'); // Add the .fas (full heart) from classes in <i> element
                    watchlistIcon.classList.remove('far'); // Remove the .far (empty heart) from classes in <i> element
                }
                else {
                    watchlistIcon.classList.add('far'); // Add the .fas (full heart) from classes in <i> element
                    watchlistIcon.classList.remove('fas'); // Remove the .far (empty heart) from classes in <i> element
                }
            });

        } else {
            alert("Probleme")
        }
    })
}
