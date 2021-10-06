const search = instantsearch({
    appId: "Z74QGMKVYK",
    apiKey: "407bb315626d2c11e567d64095f451ee",
    indexName: "courses",
    searchParameters: {
        hitsPerPage: 10,
        attributesToSnippet: ["description:24"],
        snippetEllipsisText: " [...]"
    }
});

search.addWidget(
    instantsearch.widgets.hits({
        container: "#hits",
            templates: {
                empty: "No results.",
                item: function (hit) {
                    return hitTemplate(hit);
                }
            }
    })
);

search.addWidget(
    instantsearch.widgets.searchBox({
        container: "#searchbox",
        placeholder: "Search course",
        autofocus: false
    })
);

search.addWidget(
    instantsearch.widgets.stats({
        container: "#stats",
        templates: {
            body(hit) {
                return `<i class='far fa-clock text-warning'></i> <strong>${hit.nbHits}</strong> results found ${
                        hit.query != "" ? `for <strong>"${hit.query}"</strong>` : ``
                        } in <strong>${hit.processingTimeMS}ms</strong>`;
                }
        }
    })
);

search.addWidget(
        instantsearch.widgets.refinementList({
            container: "#categories",
            attributeName: "category",
            autoHideContainer: false,
            templates: {
                header: "Categories"
            }
        })
        );

search.addWidget(
    instantsearch.widgets.pagination({
        container: "#pagination"
    })
);

search.start();




function hitTemplate(hit) {
    return `
        <div class="clearfix mb-4 bg-light p-2">
            <article>
                <div class="post-img">
                    <a href="${hit.link}" target="_blank">
                        <img src="${hit.image}" class="rounded float-left  mr-3" style=" max-width: 300px;">
                    </a>
                </div>
    
                <div class="post-content ">
                    <h2 class="entry-title">
                        <a href="${hit.link}" rel="bookmark">
                            ${hit._highlightResult.title.value}
                        </a>
                    </h2>
                    <div class="post-excerpt">
                        By <b> ${hit.institute}</b>
                    </div>
                    <div class="post-excerpt">
                        ${hit._snippetResult.description.value}
                    </div>
                </div>
            </article>
        </div>`;
}