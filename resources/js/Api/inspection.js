
import request from "@/Http";

export default {

    fetchByType: (type) => request.get(`/inspection/${type}`),

    fetchByEntity: (entity_identifier) => request.get(`/inspection/entity/${entity_identifier}`),

    fetchByCode: (code) => request.get(`/inspection/show/${code}`),

    fetchStates: () => request.get("/inspection"),



    destroy: (code) => request.delete(`/inspection/${code}`)
}