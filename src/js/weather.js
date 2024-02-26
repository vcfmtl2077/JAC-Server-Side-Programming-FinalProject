document.addEventListener('DOMContentLoaded', function() {
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
