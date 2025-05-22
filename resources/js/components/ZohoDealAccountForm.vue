<template>
    <form id="zoho-form" @submit.prevent="submitForm">
        <h3>Deal</h3>
        <input v-model="dealName" type="text" placeholder="Deal Name" />
        <div v-if="errors.dealName" class="form-error">{{ errors.dealName[0] }}</div>

        <select v-model="dealStage">
            <option disabled value="">Select Deal Stage</option>
            <option v-for="stage in stages" :key="stage" :value="stage">{{ stage }}</option>
        </select>
        <div v-if="errors.dealStage" class="form-error">{{ errors.dealStage[0] }}</div>

        <h3>Account</h3>
        <input v-model="accountName" type="text" placeholder="Account Name" />
        <div v-if="errors.accountName" class="form-error">{{ errors.accountName[0] }}</div>

        <input v-model="accountWebsite" type="url" placeholder="Account Website" />
        <div v-if="errors.accountWebsite" class="form-error">{{ errors.accountWebsite[0] }}</div>

        <input v-model="accountPhone" type="tel" placeholder="Account Phone" />
        <div v-if="errors.accountPhone" class="form-error">{{ errors.accountPhone[0] }}</div>

        <button :disabled="isSubmitting" type="submit">
            <span v-if="isSubmitting">Submitting…</span>
            <span v-else>Submit</span>
        </button>

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
    isSubmitting,
    submitForm,
} = useZohoForm()
</script>

