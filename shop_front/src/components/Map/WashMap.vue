<template>
  <div v-show="$route.path === '/categories'" class="map-container">
    <h2>Автомойки на карте</h2>
    <div id="map"></div>

    <!-- Модальное окно записи -->
    <div v-if="isBookingModalOpen" class="modal">
      <div class="modal-content">
        <h3>Запись на автомойку</h3>
        <p><strong>{{ selectedCarWash?.name }}</strong></p>
        <form @submit.prevent="submitBooking">
          <label>
            Имя:
            <input type="text" v-model="bookingData.name" required />
          </label>
          <label>
            Телефон:
            <input type="tel" v-model="bookingData.phone" required />
          </label>
          <button type="submit">Записаться</button>
          <button type="button" @click="closeBookingModal">Отмена</button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
/* global ymaps */
import { nextTick } from "vue";

export default {
  data() {
    return {
      carWashes: [],
      isBookingModalOpen: false,
      selectedCarWash: null,
      bookingData: {
        name: "",
        phone: "",
      },
    };
  },

  mounted() {
    if (!window.ymaps) {
      const script = document.createElement("script");
      script.src =
          "https://api-maps.yandex.ru/2.1/?apikey=76f6b516-5c18-4370-aafa-99336be9b94a&lang=ru_RU";
      script.onload = () => this.fetchCarWashes();
      document.head.appendChild(script);
    } else {
      this.fetchCarWashes();
    }
  },

  methods: {
    async fetchCarWashes() {
      try {
        const response = await fetch("http://localhost:8000/api/car-washes");
        if (!response.ok) throw new Error("Ошибка загрузки данных");
        const data = await response.json();
        this.carWashes = data.data || data;
        this.waitForMapElement();
      } catch (error) {
        console.error("Ошибка при получении данных:", error);
      }
    },

    waitForMapElement() {
      nextTick(() => {
        const mapElement = document.getElementById("map");
        if (!mapElement) {
          setTimeout(this.waitForMapElement, 100);
          return;
        }
        this.initMap();
      });
    },

    initMap() {
      ymaps.ready(() => {
        this.map = new ymaps.Map("map", {
          center: [43.222, 76.851],
          zoom: 12,
        });
        this.addCarWashes();
      });
    },

    addCarWashes() {
      this.carWashes.forEach((wash) => {
        if (wash.latitude && wash.longitude) {
          let placemark = new ymaps.Placemark(
              [wash.latitude, wash.longitude],
              {
                balloonContent: `
                <strong>${wash.name}</strong><br>
                Описание: ${wash.description || "Нет информации"}<br>
                Телефон: ${wash.phone || "Не указан"}<br>
                Instagram: <a href="${wash.instagram}" target="_blank">${wash.instagram || "Не указан"}</a><br>
                <button class="booking-btn" onclick="window.vueInstance.openBookingModal('${wash.id}')">Записаться</button>
              `,
              }
          );
          this.map.geoObjects.add(placemark);
        }
      });

      // Добавляем ссылку на Vue-инстанс для работы кнопки
      window.vueInstance = this;
    },

    openBookingModal(carWashId) {
      this.selectedCarWash = this.carWashes.find((w) => w.id == carWashId);
      this.isBookingModalOpen = true;
    },

    closeBookingModal() {
      this.isBookingModalOpen = false;
      this.bookingData.name = "";
      this.bookingData.phone = "";
    },

    async submitBooking() {
      if (!this.selectedCarWash) return;

      try {
        const response = await fetch("http://localhost:8000/api/booking/store", {
          method: "POST",
          headers: {"Content-Type": "application/json"},
          body: JSON.stringify({
            car_wash_id: this.selectedCarWash.id,
            name: this.bookingData.name,
            phone: this.bookingData.phone,
          }),
        });

        if (!response.ok) throw new Error("Ошибка записи");
        alert("Вы успешно записаны!");
        this.closeBookingModal();
      } catch (error) {
        console.error("Ошибка при записи:", error);
        alert("Ошибка записи, попробуйте позже.");
      }
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

.modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.6);
  display: flex;
  align-items: center;
  justify-content: center;
  animation: fadeIn 0.3s ease-in-out;
}

.modal-content {
  background: white;
  padding: 30px;
  border-radius: 12px;
  text-align: center;
  width: 400px;
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
  transform: scale(0.9);
  animation: zoomIn 0.3s ease-in-out forwards;
}

h3 {
  margin-bottom: 15px;
  font-size: 22px;
  color: #333;
}

label {
  display: block;
  text-align: left;
  margin-bottom: 10px;
  font-weight: bold;
}

input {
  width: 100%;
  padding: 10px;
  margin-top: 5px;
  border: 1px solid #ccc;
  border-radius: 6px;
  font-size: 16px;
}

button {
  width: 100%;
  padding: 12px;
  margin-top: 15px;
  border: none;
  border-radius: 6px;
  font-size: 16px;
  cursor: pointer;
  transition: 0.2s;
}

button[type="submit"] {
  background: #007bff;
  color: white;
}

button[type="submit"]:hover {
  background: #0056b3;
}

button[type="button"] {
  background: #ccc;
  color: #333;
}

button[type="button"]:hover {
  background: #bbb;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

@keyframes zoomIn {
  from {
    transform: scale(0.9);
  }
  to {
    transform: scale(1);
  }
}
</style>