const searchbtn = document.getElementById('searchbtn');
const search_ = document.getElementById('search_value');

function search_now() {

    if (search_.value.trim() !== '') {
        location.href = '/search/'+search_.value.trim();
    } else {
        return false;
    }
}
searchbtn.onclick = search_now;

search_.addEventListener("keypress", (e) => {
    if (e.key === "Enter") {
        search_now()
    }
})