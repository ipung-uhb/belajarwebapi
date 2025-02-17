// api url
const api_url = "https://dummyjson.com/products";

// Defining async function
async function getapi(url) {
    // Storing response
    const response = await fetch(url);
    // Storing data in form of JSON
    var data = await response.json();
    console.log(data);
    if (response) {
        hideloader();
    }
    show(data);
}
// Calling that async function
getapi(api_url);
// Function to hide the loader
function hideloader() {
    document.getElementById('loading').style.display = 'none';
}
// Function to define innerHTML for HTML table
function show(data) {
    let tab =
        `<tr>
    <th>Product</th>
    <th>Description</th>
    <th>Price</th>
    <th>Brand</th>
    <th>Images</th>
    </tr>`
    // Loop to access all rows
    for (let r of data.products) {
        tab += `<tr>
    <td>${r.title} </td>
    <td>${r.description}</td>
    <td>${r.price}</td>
    <td>${r.brand}</td>
    <td><img src="${r.images[1]}" width="100"/></td>
    </tr>`;
    }
    // Setting innerHTML as tab variable
    document.getElementById("employees").innerHTML = tab;
}