// Search optimization functionality
class SearchOptimizer {
    constructor(options = {}) {
        this.searchInput = document.querySelector(options.searchInputSelector || '#search-input');
        this.resultsContainer = document.querySelector(options.resultsContainerSelector || '#search-results');
        this.debounceTime = options.debounceTime || 300;
        this.minSearchLength = options.minSearchLength || 2;
        this.searchTimeout = null;
        this.searchHistory = new Set();
    }

    // Initialize the search functionality
    init() {
        if (!this.searchInput) return;

        this.searchInput.addEventListener('input', this.debounceSearch.bind(this));
        this.searchInput.addEventListener('focus', this.showSearchHistory.bind(this));
    }

    // Debounce function to prevent excessive API calls
    debounceSearch(event) {
        clearTimeout(this.searchTimeout);
        const searchTerm = event.target.value.trim();

        if (searchTerm.length < this.minSearchLength) {
            this.clearResults();
            return;
        }

        this.searchTimeout = setTimeout(() => {
            this.performSearch(searchTerm);
        }, this.debounceTime);
    }

    // Perform the actual search
    async performSearch(searchTerm) {
        try {
            // Add to search history
            this.searchHistory.add(searchTerm);

            // Here you would typically make an API call
            // For demonstration, we'll simulate a search
            const results = await this.simulateSearch(searchTerm);
            this.displayResults(results, searchTerm);
        } catch (error) {
            console.error('Search failed:', error);
            this.displayError('Search failed. Please try again.');
        }
    }

    // Display search results with highlighted matches
    displayResults(results, searchTerm) {
        if (!this.resultsContainer) return;

        this.resultsContainer.innerHTML = '';

        if (results.length === 0) {
            this.resultsContainer.innerHTML = '<p>No results found</p>';
            return;
        }

        const resultsList = document.createElement('ul');
        resultsList.className = 'search-results-list';

        results.forEach(result => {
            const li = document.createElement('li');
            const highlightedText = this.highlightMatch(result, searchTerm);
            li.innerHTML = highlightedText;
            resultsList.appendChild(li);
        });

        this.resultsContainer.appendChild(resultsList);
    }

    // Highlight matching text in results
    highlightMatch(text, searchTerm) {
        const regex = new RegExp(`(${searchTerm})`, 'gi');
        return text.replace(regex, '<mark>$1</mark>');
    }

    // Show search history
    showSearchHistory() {
        if (!this.resultsContainer || this.searchHistory.size === 0) return;

        const historyList = document.createElement('ul');
        historyList.className = 'search-history-list';

        this.searchHistory.forEach(term => {
            const li = document.createElement('li');
            li.textContent = term;
            li.addEventListener('click', () => {
                this.searchInput.value = term;
                this.performSearch(term);
            });
            historyList.appendChild(li);
        });

        this.resultsContainer.innerHTML = '';
        this.resultsContainer.appendChild(historyList);
    }

    // Clear search results
    clearResults() {
        if (this.resultsContainer) {
            this.resultsContainer.innerHTML = '';
        }
    }

    // Display error message
    displayError(message) {
        if (this.resultsContainer) {
            this.resultsContainer.innerHTML = `<p class="error">${message}</p>`;
        }
    }

    // Simulate search (replace with actual API call)
    async simulateSearch(searchTerm) {
        // Simulate API delay
        await new Promise(resolve => setTimeout(resolve, 500));
        
        // Sample data - replace with actual API response
        const sampleData = [
            'The Shawshank Redemption',
            'The Godfather',
            'The Dark Knight',
            'Pulp Fiction',
            'Forrest Gump'
        ];

        return sampleData.filter(item => 
            item.toLowerCase().includes(searchTerm.toLowerCase())
        );
    }
}

// Add some basic styles
const style = document.createElement('style');
style.textContent = `
    .search-results-list, .search-history-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .search-results-list li, .search-history-list li {
        padding: 8px 12px;
        border-bottom: 1px solid #eee;
        cursor: pointer;
    }

    .search-results-list li:hover, .search-history-list li:hover {
        background-color: #f5f5f5;
    }

    mark {
        background-color: #ffeb3b;
        padding: 2px;
        border-radius: 2px;
    }

    .error {
        color: #f44336;
        padding: 8px;
    }
`;
document.head.appendChild(style);

// Usage example:
// const searchOptimizer = new SearchOptimizer({
//     searchInputSelector: '#search-input',
//     resultsContainerSelector: '#search-results',
//     debounceTime: 300,
//     minSearchLength: 2
// });
// searchOptimizer.init(); 