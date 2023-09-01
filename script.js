document.addEventListener('DOMContentLoaded', function() {
    const fetchButton = document.querySelector('button');
    fetchButton.addEventListener('click', fetchNews);
});

function fetchNews() {
    const rssUrl = document.getElementById('rssUrl').value;

    if (!rssUrl) {
        alert("Please enter an RSS feed URL.");
        return;
    }

    // Use jQuery's get() method to make an AJAX call
    $.get('fetchRss.php', { url: rssUrl }, function(data) {
        // Check if the data returned is an array and not empty
        if (Array.isArray(data) && data.length > 0) {
            displayNews(data);
        } else {
            alert("Failed to fetch news.");
        }
    });
}

function displayNews(newsArray) {
    const newsList = document.getElementById('newsList');
    newsList.innerHTML = ''; // Clear previous news

    // Iterate over each news item
    newsArray.forEach(news => {
        const newsItem = `
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><a href="${news.link}" target="_blank">${news.title}</a></h5>
                    <p class="card-text">${news.description}</p>
                    <p class="card-text"><small class="text-muted">${news.pubDate}</small></p>
                </div>
            </div>
        `;

        // Append the formatted news item to the list
        newsList.innerHTML += newsItem;
    });
}
