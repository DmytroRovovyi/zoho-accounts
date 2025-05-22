// composables/useZohoForm.js
import { ref } from 'vue';

export default function useZohoForm() {
    const dealName = ref('');
    const dealStage = ref('');
    const accountName = ref('');
    const accountWebsite = ref('');
    const accountPhone = ref('');
    const successMessage = ref('');
    const errorMessage = ref('');
    const errors = ref({});
    const stages = ['Qualification', 'Proposal', 'Negotiation', 'Closed Won', 'Closed Lost'];

    const validate = () => {
        errors.value = {};
        if (!dealName.value) errors.value.dealName = 'Deal Name is required.';
        if (!dealStage.value) errors.value.dealStage = 'Deal Stage is required.';
        if (!accountName.value) errors.value.accountName = 'Account Name is required.';
        if (accountWebsite.value && !isValidURL(accountWebsite.value)) {
            errors.value.accountWebsite = 'Invalid URL.';
        }
        if (!accountPhone.value) {
            errors.value.accountPhone = 'Account Phone is required.';
        } else if (!isValidPhone(accountPhone.value)) {
            errors.value.accountPhone = 'Invalid phone number.';
        }
        return Object.keys(errors.value).length === 0;
    };

    const isValidURL = (url) => {
        try {
            new URL(url);
            return true;
        } catch {
            return false;
        }
    };

    const isValidPhone = (phone) => {
        const phoneRegex = /^\+?[0-9\s\-]{7,15}$/;
        return phoneRegex.test(phone);
    };

    const submitForm = async () => {
        if (!validate()) return;
        successMessage.value = '';
        errorMessage.value = '';

        try {
            const response = await fetch('/api/zoho/create', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    dealName: dealName.value,
                    dealStage: dealStage.value,
                    accountName: accountName.value,
                    accountWebsite: accountWebsite.value,
                    accountPhone: accountPhone.value,
                }),
            });

            const result = await response.json();
            if (response.ok) {
                successMessage.value = 'Deal and Account created successfully!';
                dealName.value = '';
                dealStage.value = '';
                accountName.value = '';
                accountWebsite.value = '';
                accountPhone.value = '';
            } else {
                errorMessage.value = result.message || 'Failed to create records.';
            }
        } catch (err) {
            errorMessage.value = 'Network error or server problem.';
        }
    };

    return {
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
    };
}
