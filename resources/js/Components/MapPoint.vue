<template>
  <div v-if="hasCoordinates" ref="mapContainer" class="w-full h-64 rounded-2xl shadow-clay-inset overflow-hidden"></div>
  <p v-else class="text-sm text-clay-muted italic">
    Sin ubicacion en el mapa todavia (la direccion no se pudo ubicar).
  </p>
</template>

<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import markerIcon2x from 'leaflet/dist/images/marker-icon-2x.png';
import markerIcon from 'leaflet/dist/images/marker-icon.png';
import markerShadow from 'leaflet/dist/images/marker-shadow.png';

// Fix conocido de Leaflet con bundlers como Vite: sin esto los pines
// del mapa no se ven porque Leaflet intenta resolver las rutas de los
// iconos de forma relativa al HTML, no al bundle.
delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
  iconRetinaUrl: markerIcon2x,
  iconUrl: markerIcon,
  shadowUrl: markerShadow,
});

const props = defineProps({
  lat: { type: [Number, String], default: null },
  lng: { type: [Number, String], default: null },
  label: { type: String, default: '' },
});

const hasCoordinates = computed(() => props.lat !== null && props.lng !== null && props.lat !== '' && props.lng !== '');

const mapContainer = ref(null);
let map = null;
let marker = null;

function renderMap() {
  if (!hasCoordinates.value || !mapContainer.value) return;

  const latitude = Number(props.lat);
  const longitude = Number(props.lng);

  if (!map) {
    map = L.map(mapContainer.value).setView([latitude, longitude], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap contributors',
    }).addTo(map);
    marker = L.marker([latitude, longitude]).addTo(map);
  } else {
    map.setView([latitude, longitude], 15);
    marker.setLatLng([latitude, longitude]);
  }

  if (props.label) {
    marker.bindPopup(props.label);
  }
}

onMounted(renderMap);
watch(() => [props.lat, props.lng], renderMap);
</script>
