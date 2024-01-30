import { ref, onBeforeUnmount, computed, watch } from "vue";

// global pet state
const petState = ref({});

// preset pet responses
const petResponses = {
    noAvatar: [{ speak: null, think: "I need a picture" }, { speak: "Add my picture" }],
    newAvatar: [{ speak: null, think: "I'm so cute" }, { speak: "I like that pic" }, {
        speak: null,
        think: "That's my good side"
    }],
    weightGain: [{ speak: null, think: "I'm getting bigger" }],
    weightLoss: [{ speak: null, think: "I'm shrinking" }],
    speciesChange: [{ speak: null, think: "I changed species?" }],
    species: {
        dog: [{ speak: "woof" }],
        cat: [{ speak: "meow" }, { speak: null, think: "Is it nap time yet?" }],
        bird: [{ speak: "tweet" }],
        fish: [{ speak: null, think: "fish can't talk" }],
        horse: [{ speak: "neigh" }],
        reptile: [{ speak: "ssssss" }],
    }
};

petResponses.added = function(event) {
    const { pet = {} } = event;
    const possibleResponses = [];
    if (pet.avatar) {
        possibleResponses.push(...petResponses.newAvatar);
    } else {
        possibleResponses.push(...[{
            speak: null,
            think: "I need a picture",
            route: { name: "pets.edit", params: pet._id }
        }, { speak: "Add my picture", route: { name: "pets.edit", params: pet._id } }]);
    }
    return possibleResponses;
};

petResponses.edited = function(event) {
    const { oldPet = {}, newPet = {} } = event;
    const possibleResponses = [];
    if (newPet.avatar && !oldPet.avatar) {
        possibleResponses.push(...petResponses.newAvatar);
    } else if (oldPet.avatar && !newPet.avatar) {
        possibleResponses.push(...[{
            speak: null,
            think: "I need a picture",
            route: { name: "pets.edit", params: newPet._id }
        }, { speak: "Add my picture", route: { name: "pets.edit", params: newPet._id } }]);
    }
    if (newPet.weight > oldPet.weight) {
        possibleResponses.push(...petResponses.weightGain);
    } else if (oldPet.weight > newPet.weight) {
        possibleResponses.push(...petResponses.weightLoss);
    }
    if (newPet.species !== oldPet.species) {
        possibleResponses.push(...petResponses.speciesChange);
    }
    return possibleResponses;
};

// get one random response from array of possible responses
const pickResponse = (responses) => {
    return responses[Math.floor(Math.random() * responses.length)] || {};
};

// get one of possible pet responses to event
const processEvent = (event) => {
    console.log(event);
    const responses = petResponses[event.name];
    if (typeof responses === "function") {
        return pickResponse(responses(event));
    } else {
        return pickResponse(responses);
    }
};

// get one of possible pet responses to current state
const currentResponse = (pet) => {
    const possibleResponses = [];
    possibleResponses.push(...petResponses["species"][pet.species]);
    if (!pet.avatar) {
        possibleResponses.push(...[{
            speak: null,
            think: "I need a picture",
            route: { name: "pets.edit", params: pet._id }
        }, { speak: "Add my picture", route: { name: "pets.edit", params: pet._id } }]);
    }
    return pickResponse(possibleResponses);
};

const updatePetStatus = (pet) => {

    const { _id: id } = pet;

    petState.value[id]["status"] = null;

    if (!pet.settings) {
        petState.value[id]["status"] = "Review pet settings";
        petState.value[id]["route"] = { name: "pets.settings", params: id };
    }
};

// periodic activity in pet state
const brainTick = (pet) => {
    const { _id: id, name } = pet;

    const randomize = Math.random();
    if (randomize < .25) {
        petState.value[id]["route"] = null;
        // send a current response
        petState.value[id] = { ...petState.value[id], ...currentResponse(pet) };
    } else if (randomize < .85) {
        // erase any previous responses
        petState.value[id] = { ...petState.value[id], think: null, speak: null, route: null };
    }

    // status response
    if (!petState.value[id]["speak"] && !petState.value[id]["think"]) {
        petState.value[id]["route"] = null;
        updatePetStatus(pet);
    }
};

export default function usePetAI(pet, event = {}) {

    const { _id: id, name } = pet;

    if (id) {

        if (!(id in petState.value)) {
            // init pet state object
            petState.value[id] = {};
        }

        if (!petState.value[id].ticker) {
            updatePetStatus(pet);
            // start thinking interval
            petState.value[id].ticker = true;
            let ticker = setInterval(() => {
                brainTick(pet);
            }, Math.floor(Math.random() * (40000) + 10000));

            onBeforeUnmount(() => {
                // stop thinking interval
                clearInterval(ticker);
                petState.value[id].ticker = false;
            });
        }

        if (Object.keys(event).length) {
            // merge event result with pet state
            petState.value[id] = { ...petState.value[id], ...processEvent(event) };
        }
    }

    // set state properties to be cleared after this view
    const clearPetState = (petId, property) => {
        if (petId && property && petState.value[petId] && petState.value[petId][property]) {
            onBeforeUnmount(() => {
                petState.value[petId][property] = null;
            });
        }
    };

    return {
        petState: computed(() => {
            return id ? (petState.value[id] ? petState.value[id] : {}) : petState.value;
        }), clearPetState
    };
}
