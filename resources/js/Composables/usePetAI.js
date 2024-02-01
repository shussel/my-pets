import { ref, onBeforeUnmount, computed, watch } from "vue";

// global pet state
const petState = ref({});

// preset pet responses
const petResponses = {
    newAvatar: [
        { think: { text: "I'm so cute" } },
        { speak: { text: "I like that pic" } },
        { think: { text: "That's my good side" } }
    ],
    food: [{ think: { text: "mmm, food" } }],
    poop: [{ speak: { text: "everybody poops" } }],
    weightGain: [{ think: { text: "I'm getting bigger" } }],
    weightLoss: [{ think: { text: "I'm shrinking" } }],
    speciesChange: [{ think: { text: "I changed species?" } }],
    species: {
        dog: [{ speak: { text: "woof" } }, { speak: { text: "arf" } }],
        cat: [{ speak: { text: "meow" } }, { think: { text: "Is it nap time yet?" } }, { speak: { text: "purr" } }, , { speak: { text: "mew" } }],
        bird: [{ speak: { text: "tweet" } }, { speak: { text: "chirp" } }, { speak: { text: "caw" } }, , { speak: { text: "squawk" } }],
        fish: [{ think: { text: "fish can't talk" } }],
        horse: [{ speak: { text: "neigh" } }, { speak: { text: "whinny" } }],
        reptile: [{ speak: { text: "ssssss" } }],
    }
};

petResponses.added = function(event) {
    const { pet = {} } = event;
    console.log("added", pet.name, event);
    const possibleResponses = [];
    if (pet.avatar) {
        possibleResponses.push(...petResponses.newAvatar);
    } else {
        possibleResponses.push(...[
            { think: { text: "I need a picture", route: { name: "pets.edit", params: pet._id } } },
            { speak: { text: "Add my picture", route: { name: "pets.edit", params: pet._id } } }
        ]);
    }
    console.log("possible responses", possibleResponses);
    return possibleResponses;
};

petResponses.edited = function(event) {
    const { oldPet = {}, newPet = {} } = event;
    console.log("edited", newPet.name, event);
    const possibleResponses = [];
    if (newPet.avatar && !oldPet.avatar) {
        possibleResponses.push(...petResponses.newAvatar);
    } else if (oldPet.avatar && !newPet.avatar) {
        possibleResponses.push(...[
            { think: { text: "I need a picture", route: { name: "pets.edit", params: newPet._id } } },
            { speak: { text: "Add my picture", route: { name: "pets.edit", params: newPet._id } } }
        ]);
    }
    if (newPet.weight > oldPet.weight) {
        possibleResponses.push(...petResponses.weightGain);
    } else if (oldPet.weight > newPet.weight) {
        possibleResponses.push(...petResponses.weightLoss);
    }
    if (newPet.species !== oldPet.species) {
        possibleResponses.push(...petResponses.speciesChange);
    }
    console.log("possible responses", possibleResponses);
    return possibleResponses;
};

// get one random response from array of possible responses
const pickResponse = (responses) => {
    const response = responses[Math.floor(Math.random() * responses.length)] || {};
    console.log("response", response);
    return response;
};

// get one of possible pet responses to event
const processEvent = (event) => {
    console.log("event", event);
    const responses = petResponses[event.name];
    if (typeof responses === "function") {
        return pickResponse(responses(event));
    } else {
        return pickResponse(responses);
    }
};

// get one of possible pet responses to current state
const currentResponse = (pet) => {
    const { _id: id, name, species, avatar } = pet;
    const possibleResponses = [];
    console.log("get tick response", name, pet);
    possibleResponses.push(...petResponses["species"][species]);
    if (!avatar) {
        possibleResponses.push(...[
            { think: { text: "I need a picture", route: { name: "pets.edit", params: id } } },
            { speak: { text: "Add my picture", route: { name: "pets.edit", params: id } } }
        ]);
    }
    return pickResponse(possibleResponses);
};

const updatePetStatus = (pet) => {

    const { _id: id, name } = pet;

    console.log("update status", name, pet);

    petState.value[id]["status"] = null;

    // settings review
    for (const setting of ["", "food", "poop", "sleep"]) {
        if ((!setting && !pet.settings) || (setting && !pet.settings[setting])) {
            petState.value[id]["status"] = {
                text: `Review ${setting} settings`,
                route: { name: "pets.settings", params: id }
            };
            break;
        }
    }
};

// periodic activity in pet state
const brainTick = (pet) => {
    const { _id: id, name } = pet;

    console.log("tick", name, pet);
    updatePetStatus(pet);

    const randomize = Math.random();
    if (randomize < .25) {
        petState.value[id]["route"] = null;
        // send a current response
        petState.value[id] = { ...petState.value[id], ...currentResponse(pet) };
    } else if (randomize < .85) {
        // erase any previous responses
        console.log(name, "mind blanks");
        petState.value[id] = { ...petState.value[id], think: null, speak: null };
    } else {
        console.log(name, "continuing thought");
    }
};

export default function usePetAI(pet, event = {}) {

    const { _id: id, name } = pet;

    if (id) {

        if (!(id in petState.value)) {
            // init pet state object
            petState.value[id] = {};
            console.log("init", pet);
        }

        if (Object.keys(event).length) {
            // merge event result with pet state
            petState.value[id] = { ...petState.value[id], ...processEvent(event) };

        } else if (!petState.value[id].ticker) {
            console.log("waking", name);
            updatePetStatus(pet);
            // start thinking interval
            petState.value[id].ticker = setTimeout(function think() {
                brainTick(pet);
                const nextThought = Math.floor(Math.random() * (50000) + 10000);
                console.log("next thought for", name, nextThought / 1000);
                petState.value[id].ticker = setTimeout(think, nextThought);
            }, Math.floor(Math.random() * (50000) + 10000));

            onBeforeUnmount(() => {
                // stop thinking interval
                console.log("sleeping", name);
                clearInterval(petState.value[id].ticker);
                petState.value[id].ticker = null;
            });
        }
    }

    // set state properties to be cleared after this view
    const clearPetState = (petId, property) => {
        if (petId && property && petState.value[petId] && petState.value[petId][property]) {
            console.log("set clear", petId, property);
            onBeforeUnmount(() => {
                console.log("clearing", petId, property);
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
