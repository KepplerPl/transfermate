document.addEventListener("DOMContentLoaded", function(event) {

    fetch("/api/v1/all", {
        method: "GET",
        headers: {}
    })
        .then(response => response.json())
        .then(body => {
            document.getElementById("afterLoad").style.removeProperty("display");
            let html = '<table class="center"><tbody><tr><th>Author</th><th>Book</th></tr>';
            for(let i = 0; i < body.length; i++) {
                html += "<tr><td>"+body[i].author+"</td><td>"+ (null == body[i][0].book ? "&lt;none&gt; (no books found)" : body[i][0].book)+"</td></tr>"
            }
            html += "</tbody></table>";
            populateContainer(html);
        });

    document.getElementById("searchSubmit").addEventListener("click", function(e) {
        e.preventDefault()
        submitSearch();
    });
});

function populateContainer(html) {
    const container = document.getElementById("container");
    container.innerHTML = html;
}

function submitSearch() {
    let searchValue = document.getElementById("searchText").value
    document.getElementById("searchSubmit").disabled = true;

    fetch("/api/v1/search?q="+searchValue, {
        method: "GET",
        headers: {}
    })
        .then(response => response.json())
        .then(body => {
            let author = body.author;
            delete body.author;

            let html = '<table class="center"><tbody><tr><th>Author</th><th>Book</th></tr>';

            for (const property in body) {
                html += "<tr><td>"+author+"</td><td>"+ (null == body[property].book ? "&lt;none&gt; (no books found)" : body[property].book)+"</td></tr>"
            }
            html += "</tbody></table>";

            populateContainer(html)
            document.getElementById("searchSubmit").disabled = false;

        });
}
