import axios from 'axios';

// TODO: load API base url from settings
const API_BASE_URL = "http://localhost:8000"

const eventAxios = axios.create({
  baseURL: API_BASE_URL,
  timeout: 5000
});

export async function checkAuthentication(key) {
  return new Promise(async (resolve, reject) => {
    try {
      const resp = await eventAxios.post("/checkauth", {key});
      resolve(resp.data);
    }catch (e) {
      reject(e);
    }
  });
}

export async function fetchAllEvents() {
  return new Promise(async (resolve, reject) => {
    try {
      const resp = await eventAxios.get("/events");
      resolve(resp.data);
    }catch (e) {
      reject(e);
    }
  });
}

export async function createEvent(event, key) {
  return new Promise(async (resolve, reject) => {
    try {
      const resp = await eventAxios.post("/events", {key, data: event});
      resolve(resp.data);
    }catch (e) {
      reject(e);
    }
  });
}

export async function updateEvent(event, key) {
  return new Promise(async (resolve, reject) => {
    try {
      const resp = await eventAxios.put(`/events/${event.event_id}`, {key, data: event});
      resolve(resp.data);
    }catch (e) {
      reject(e);
    }
  });
}

export async function deleteEvent(eventId, key) {
  return new Promise(async (resolve, reject) => {
    try {
      const resp = await eventAxios.delete(`/events/${eventId}`, {data: {key}});
      resolve(resp.data);
    }catch (e) {
      reject(e);
    }
  });
}

export async function createRegistration(registration) {
  return new Promise(async (resolve, reject) => {
    try {
      const resp = await eventAxios.post(`/registrations`, registration);
      resolve(resp.data);
    }catch (e) {
      reject(e);
    }
  });
}

export async function updateRegistration(registration) {
  return new Promise(async (resolve, reject) => {
    try {
      const resp = await eventAxios.put(`/registrations/${registration.registration_id}`, registration);
      resolve(resp.data);
    }catch (e) {
      reject(e);
    }
  });
}

export async function deleteRegistration(registrationId) {
  return new Promise(async (resolve, reject) => {
    try {
      const resp = await eventAxios.delete(`/registrations/${registrationId}`);
      resolve(resp.data);
    }catch (e) {
      reject(e);
    }
  });
}
