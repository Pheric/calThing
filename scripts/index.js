function collapse(parent) {
    parent.classList.toggle("active");
    let content = parent.nextElementSibling;
    if (content.style.display === "block") {
        content.style.display = "none";
    } else {
        content.style.display = "block";
    }
}