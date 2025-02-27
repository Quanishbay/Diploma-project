<template>
  <div class="map-container">
    <h2>Автомойки на карте</h2>
    <div id="map"></div>
  </div>
</template>

<script>
/* global ymaps */
export default {
  props: {
    carWashes: {
      type: Array,
      required: true
    }
  },

  watch: {
    carWashes: {
      handler() {
        if (this.map) {
          this.addCarWashes();
        }
      },
      deep: true
    }
  },

  mounted() {
    const script = document.createElement("script");
    script.src =
        "https://api-maps.yandex.ru/2.1/?apikey=76f6b516-5c18-4370-aafa-99336be9b94a&lang=ru_RU";
    script.onload = () => {
      ymaps.ready(this.initMap);
    };
    document.head.appendChild(script);
  },

  methods: {
    initMap() {
      this.map = new ymaps.Map("map", {
        center: [43.222, 76.851], // Центр Алматы
        zoom: 12
      });

      this.addCarWashes();
    },

    addCarWashes() {
      if (!this.map) return;

      this.map.geoObjects.removeAll(); // Очищаем старые метки

      this.carWashes.forEach((wash) => {
        let placemark = new ymaps.Placemark(
            [wash.latitude, wash.longitude],
            {
              balloonContent: `
              <strong>${wash.name}</strong><br>
              ${wash.description}</br>
              Адрес: ${wash.address}<br>
              Телефон: ${wash.phone}<br>
              Рейтинг: ${wash.rating}
            `,
            }
        );
        this.map.geoObjects.add(placemark);
      });
    }
  }
};
</script>

<style scoped>
.map-container {
  width: 100%;
  height: 500px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

#map {
  width: 80%;
  height: 100%;
  min-height: 600px;
}
</style>
