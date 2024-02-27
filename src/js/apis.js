document.addEventListener('DOMContentLoaded', function() {
    getCountry();
    fetchWeather();
});

function fetchWeather() {
    //Montreal JAC Weather
    const latitude = '45.5017';
    const longitude = '-73.5673';
    const url = `https://api.open-meteo.com/v1/forecast?latitude=${latitude}&longitude=${longitude}&current=temperature_2m,wind_speed_10m&hourly=temperature_2m,relative_humidity_2m,wind_speed_10m`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            displayWeather(data);
        })
        .catch(error => {
            console.error('Error fetching weather data:', error);
        });
}

function displayWeather(data) {
    const weatherDiv = document.getElementById('weather');
    const currentWeather = data?.current;
    const temperature = currentWeather?.temperature_2m;
    const windSpeed = currentWeather?.wind_speed_10m;

    weatherDiv.innerHTML = `<p>Temperature: ${temperature} °C</p>
                            <p>Wind Speed: ${windSpeed} km/h</p>`;
}

function getCountry() {
    var apiUrl = 'https://api.country.is/';

    //get current country based on the users computer's public ip address
    fetch(apiUrl)
    .then(response => response.json())
    .then(data => {
        if (data.country) {
            document.getElementById('countryResult').innerText = "Current Country:" + data.country;
        } else {
            document.getElementById('countryResult').innerText = 'No country information found for this IP.';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('countryResult').innerText = 'Error retrieving country information.';
    });
}