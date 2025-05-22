/**
 * Load all of this project's JavaScript dependencies,
 * including Vue and other libraries. This is a great starting point
 * when building robust, powerful web applications using Vue and Laravel.
 */
import './bootstrap';
import { createApp } from 'vue';
import '../css/app.css';

// Create a new Vue application instance
const app = createApp({});

// Import the ExampleComponent and register it globally
import ExampleComponent from './components/ExampleComponent.vue';
app.component('example-component', ExampleComponent);

// Import the HelloVue component and register it globally
import ZohoDealAccountForm from './components/ZohoDealAccountForm.vue'
app.component('zoho-deal-account-form', ZohoDealAccountForm)

// Mount the Vue application to the element with id="app"
app.mount('#app');
