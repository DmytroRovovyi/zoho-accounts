<template>
    <form id="zoho-form" @submit.prevent="submitForm">
        <h3>Deal</h3>
        <input v-model="dealName" type="text" placeholder="Deal Name" />
        <div v-if="errors.dealName">{{ errors.dealName }}</div>

        <select v-model="dealStage">
            <option disabled value="">Select Deal Stage</option>
            <option v-for="stage in stages" :key="stage" :value="stage">{{ stage }}</option>
        </select>
        <div v-if="errors.dealStage">{{ errors.dealStage }}</div>

        <h3>Account</h3>
        <input v-model="accountName" type="text" placeholder="Account Name" />
        <div v-if="errors.accountName">{{ errors.accountName }}</div>

        <input v-model="accountWebsite" type="url" placeholder="Account Website" />
        <div v-if="errors.accountWebsite">{{ errors.accountWebsite }}</div>

        <input v-model="accountPhone" type="tel" placeholder="Account Phone" />
        <div v-if="errors.accountPhone">{{ errors.accountPhone }}</div>

        <button type="submit">Submit</button>

        <div v-if="successMessage" class="alert-success">{{ successMessage }}</div>
        <div v-if="errorMessage" class="alert-error">{{ errorMessage }}</div>
    </form>
</template>

<script setup>
import useZohoForm from '../composables/useZohoForm.js'

const {
    dealName,
    dealStage,
    accountName,
    accountWebsite,
    accountPhone,
    successMessage,
    errorMessage,
    errors,
    stages,
    submitForm,
} = useZohoForm()
</script>

