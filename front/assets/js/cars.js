document.addEventListener("DOMContentLoaded", () => {
    const map = L.map('map').setView([55.7558, 37.6173], 11);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const cars = document.querySelectorAll('.car-card');
    cars.forEach(card => {
        const lat = parseFloat(card.dataset.lat);
        const lng = parseFloat(card.dataset.lng);
        const name = card.dataset.name;
        const parking = card.dataset.parking;
        const link = card.querySelector('.rent-btn')?.href;

        if (!isNaN(lat) && !isNaN(lng)) {
            const marker = L.marker([lat, lng]).addTo(map);
            marker.bindPopup(`
                <b>${name}</b><br>
                <small>${parking}</small><br>
                ${link ? `<a href="${link}" class="popup-btn">Арендовать</a>` : `<span style="color:gray">Недоступен</span>`}
            `);
        }
    });
});