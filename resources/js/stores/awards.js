import {defineStore} from "pinia";
import {ref} from "vue";

export const useAwardsStore = defineStore("awards", () => {
    const activeFilters = ref({
        'keywords': [],
        'industry': [],
        'businessFunction': [],
        'location': [],

    });


    const checkedAwards = ref([]);
    const toggleCheckbox = (awardId) => {
        const index = checkedAwards.value.indexOf(awardId);
        if (index === -1) {
            checkedAwards.value.push(awardId);
        } else {
            checkedAwards.value.splice(index, 1);
        }
    };

    const toggleCheckboxByValue = (awardId, chk) => {
        const index = checkedAwards.value.indexOf(awardId);
        if (chk && index === -1) {
            checkedAwards.value.push(awardId);
        } else if (!chk && index !== -1) {
            checkedAwards.value.splice(index, 1);
        }
    };

    const updateKeywords = (keyword) => {
        if(!activeFilters.value.keywords.includes(keyword)){
            activeFilters.value.keywords.push(keyword);
        }
    };

    return {
        activeFilters,
        checkedAwards,
        toggleCheckbox,
        toggleCheckboxByValue,
        updateKeywords
    };
});
