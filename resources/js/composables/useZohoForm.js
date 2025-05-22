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
    const isSubmitting = ref(false);

    const stages = ['Qualification', 'Proposal', 'Negotiation', 'Closed Won', 'Closed Lost'];

    const submitForm = async () => {
        successMessage.value = '';
        errorMessage.value = '';
        errors.value = {};
        isSubmitting.value = true;

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

            if (response.status === 422) {
                const data = await response.json();
                errors.value = data.errors || {};
                return;
            }

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
        } finally {
            isSubmitting.value = false;
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
        isSubmitting,
        submitForm,
    };
}
