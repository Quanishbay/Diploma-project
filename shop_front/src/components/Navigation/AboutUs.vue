<template>
  <div class="map-container">
    <h2>Автомойки на карте</h2>
    <div id="map"></div>
  </div>
</template>

<script>
/* global ymaps */
export default {
  mounted() {
    console.log("Монтирование компонента...");

    const script = document.createElement("script");
    script.src =
        "https://api-maps.yandex.ru/2.1/?apikey=76f6b516-5c18-4370-aafa-99336be9b94a&lang=ru_RU";
    script.onload = () => {
      console.log("Yandex Maps загружен");
      this.initMap();
    };
    script.onerror = () => {
      console.error("Ошибка загрузки Yandex Maps");
    };
    document.head.appendChild(script);
  },
  methods: {
    initMap() {
      console.log("Инициализация карты...");
      ymaps.ready(() => {
        console.log("ymaps.ready() сработал!");
        this.map = new ymaps.Map("map", {
          center: [43.222, 76.851], // Центр Алматы
          zoom: 12,
        });

        // Добавляем несколько автомоек вручную (пример)
        this.addCarWashes([
          { coords: [43.238, 76.901], name: "Автомойка 1" },
          { coords: [43.215, 76.850], name: "Автомойка 2" },
          { coords: [43.250, 76.820], name: "Автомойка 3" },
        ]);
      });
    },

    addCarWashes(carWashes) {
      carWashes.forEach((wash) => {
        let placemark = new ymaps.Placemark(wash.coords, {
          balloonContent: wash.name,
        });
        this.map.geoObjects.add(placemark);
      });
    },
  },
};
</script>

<style scoped>
.map-container {
  width: 100%;
  height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

#map {
  width: 80%;
  min-height: 500px;
  height: 100%;
  margin: 0 auto;
  display: block;
}
</style>
