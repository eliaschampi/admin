
import request from "@/Http";

export default {

    fetchByType: (type) => request.get(`/inspection/${type}`),

    fetchByEntity: (entity_identifier) => request.get(`/inspection/entity/${entity_identifier}`),

    fetchByCode: (code) => request.get(`/inspection/show/${code}`),

    store: (data) => request.post("/inspection", data),

    update: (data, code) => request.put(`/inspection/${code}`, data),

    destroy: (code) => request.delete(`/inspection/${code}`)
}