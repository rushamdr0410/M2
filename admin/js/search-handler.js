// Initialize search functionality when the DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.querySelector('.search-form');
    const searchInput = searchForm.querySelector('.search-input');
    const searchButton = searchForm.querySelector('.search-button');
    
    if (!searchForm || !searchInput || !searchButton) {
        console.error('Search elements not found');
        return;
    }
    
    // Create results container
    const resultsContainer = document.createElement('div');
    resultsContainer.id = 'search-results';
    resultsContainer.style.cssText = `
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: #232323;
        border-radius: 0 0 25px 25px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        max-height: 400px;
        overflow-y: auto;
        display: none;
    `;
    searchForm.appendChild(resultsContainer);

    let searchTimeout;

    // Handle search input
    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.trim();
        
        // Clear previous timeout
        if (searchTimeout) {
            clearTimeout(searchTimeout);
        }

        // Hide results if search term is too short
        if (searchTerm.length < 2) {
            resultsContainer.style.display = 'none';
            return;
        }

        // Set new timeout for search
        searchTimeout = setTimeout(async () => {
            try {
                const response = await fetch(`https://api.themoviedb.org/3/search/multi?api_key=99e2fa37c0f75b95a971c97b093025cc&language=en-US&query=${encodeURIComponent(searchTerm)}&page=1&include_adult=false`);
                const data = await response.json();
                
                if (!data.results) {
                    throw new Error('Invalid API response');
                }

                displayResults(data.results);
            } catch (error) {
                console.error('Search failed:', error);
                resultsContainer.innerHTML = '<p style="padding: 15px; color: #fff;">Error performing search. Please try again.</p>';
            }
        }, 300);
    });

    // Prevent form submission
    searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const searchTerm = searchInput.value.trim();
        if (searchTerm.length >= 2) {
            searchInput.dispatchEvent(new Event('input'));
        }
    });

    // Show/hide results container on focus/blur
    searchInput.addEventListener('focus', function() {
        if (searchInput.value.trim().length >= 2) {
            resultsContainer.style.display = 'block';
        }
    });

    document.addEventListener('click', function(e) {
        if (!searchForm.contains(e.target)) {
            resultsContainer.style.display = 'none';
        }
    });

    function displayResults(results) {
        resultsContainer.innerHTML = '';

        if (results.length === 0) {
            resultsContainer.innerHTML = '<p style="padding: 15px; color: #fff;">No results found</p>';
            return;
        }

        const resultsList = document.createElement('ul');
        resultsList.className = 'search-results-list';
        resultsList.style.cssText = `
            list-style: none;
            padding: 0;
            margin: 0;
        `;

        results.forEach(result => {
            if (!result.title && !result.name) return; // Skip items without titles

            const li = document.createElement('li');
            li.style.cssText = `
                padding: 10px 15px;
                display: flex;
                align-items: center;
                gap: 10px;
                cursor: pointer;
                transition: background-color 0.3s;
            `;
            li.onmouseover = () => li.style.backgroundColor = '#2c2c2c';
            li.onmouseout = () => li.style.backgroundColor = 'transparent';

            if (result.poster_path) {
                const img = document.createElement('img');
                img.src = `https://image.tmdb.org/t/p/w92${result.poster_path}`;
                img.style.cssText = `
                    width: 45px;
                    height: 68px;
                    object-fit: cover;
                    border-radius: 4px;
                `;
                li.appendChild(img);
            }

            const content = document.createElement('div');
            content.style.cssText = `
                flex: 1;
                color: #fff;
            `;

            const title = document.createElement('div');
            title.textContent = result.title || result.name;
            title.style.fontWeight = 'bold';

            const type = document.createElement('div');
            type.textContent = result.media_type === 'movie' ? 'Movie' : 'TV Show';
            type.style.cssText = `
                font-size: 0.8em;
                color: #61DAFB;
                margin-top: 4px;
            `;

            content.appendChild(title);
            content.appendChild(type);
            li.appendChild(content);

            // Add click handler to navigate to details page
            li.addEventListener('click', () => {
                const page = result.media_type === 'movie' ? 'movie_details.php' : 'tvshow_details.php';
                window.location.href = `${page}?tmdb_id=${result.id}`;
            });

            resultsList.appendChild(li);
        });

        resultsContainer.appendChild(resultsList);
        resultsContainer.style.display = 'block';
    }
}); 