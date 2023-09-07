<?php
include 'api.php';
include 'redirect.php';
// echo "hello";
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <title>Mini URL</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
        <link href="style.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
            crossorigin="anonymous"></script>
    </head>

    <body>
        <section>
            <div class="main-container">
                <h1 class="text display-5 fw-bold text-center">Mini URL</h1>
                <br>
                <div class="col-lg-6 mx-auto">
                    <form id="url-form" class="row g-3 needs-validation" onsubmit="return shortenURL()" novalidate>
                        <div class="mb-3">
                            <label for="long-url" class="text form-label">Enter your URL</label>
                            <input type="url" class="form-box form-control" id="long-url"
                                placeholder="https://www.example.com/asdf" required>
                            <div class="invalid-feedback">
                                Please provide a valid URL.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="short-url" class="text form-label">Your Mini URL</label>
                            <div class="input-group mb-3">
                                <span class="text form-box-fixed input-group-text"
                                    id="basic-addon3">localhost:8888/</span>
                                <input type="text" class="form-box form-control" id="short-url"
                                    placeholder="eg. abcde (Optional)" aria-describedby="basic-addon3">
                                <div id="invalid-short-url" class="invalid-feedback">
                                </div>
                            </div>
                        </div>
                        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                            <button type="submit" class="btn btn-primary btn-lg px-4 gap-3" id="shortenIt">Shorten
                                It!</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </body>

</html>

<script>
    async function checkIfExists(shortURL) {
        const endpoint = 'api.php?action=get_short_urls';
        try {
            const response = await fetch(endpoint);
            const data = await response.json();
            const shortURLs = data.map(item => item.short_url);
            if (shortURLs.includes(shortURL)) {
                document.getElementById("invalid-short-url").innerHTML = "Short URL already exists";
                return true;
            }
            else {
                return false;
            }
        } catch (error) {
            console.error('Error fetching data:', error);
            throw error;
        }
    }

    function isAlphaNumeric(str) {
        return /^[a-zA-Z0-9]+$/.test(str);
    }

    async function validateURL(longURL, shortURL) {
        if (longURL == '') {
            return false;
        }
        if (shortURL != '') {
            if (!isAlphaNumeric(shortURL)) {
                return false;
            }
            const exists = await checkIfExists(shortURL);
            if (!exists) {
                return true;
            }
            else {
                return false;
            }
        }
        return true;
    }

    (function () {
        'use strict';
        var urlForm = document.getElementById('url-form');
        urlForm.addEventListener('submit', async function (event) {
            event.preventDefault();
            var longURL = document.getElementById('long-url').value;
            var shortURL = document.getElementById('short-url').value;
            const isValid = await validateURL(longURL, shortURL);
            if (!isValid) {
                document.getElementById('short-url').classList.remove('is-valid');
                document.getElementById('short-url').classList.add('is-invalid');
                event.stopPropagation();
                return;
            }
            else {
                document.getElementById('short-url').classList.remove('is-invalid');
                document.getElementById('short-url').classList.add('is-valid');
                document.getElementById("invalid-short-url").innerHTML = "";
            }
            urlForm.classList.add('was-validated');
            document.getElementById('shortenIt').disabled = true;
        }, false);
    })();

    async function hashURL() {
        var shortURL = document.getElementById('short-url').value;
        if (shortURL != '') {
            const exists = await checkIfExists(hash);
            if (!exists) {
                return shortURL;
            }
        }
        var letters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        var hash = '';
        for (var i = 0; i < 6; i++) {
            hash += letters.charAt(Math.floor(Math.random() * letters.length));
        }
        const exists = await checkIfExists(hash);
        if (!exists) {
            return hash;
        }
        else {
            hashURL();
        }
    }

    async function insertShortURL(longURL, shortURL) {
        const endpoint = 'api.php?action=insert_short_url';
        const data = 'original_url=' + encodeURIComponent(longURL) + '&short_url=' + encodeURIComponent(shortURL) + '&action=insert_short_url';

        try {
            const response = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                },
                body: data
            });
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            const responseText = await response.text();
            console.log('Data inserted successfully:', responseText);
        } catch (error) {
            console.error('Error inserting data:', error);
        }
    }

    async function shortenURL() {
        var longURL = document.getElementById('long-url').value;
        var shortURL = await hashURL();
        document.getElementById('short-url').value = shortURL;
        insertShortURL(longURL, shortURL);
    }
</script>