<template>
  <div
    :id="id"
    aria-hidden="true"
    class="modal fade"
    role="dialog"
    tabindex="-1"
  >
    <div :class="size" class="modal-dialog cascading-modal" role="document">
      <div class="modal-content">
        <div class="modal-header text-white">
          <h4 class="title">
            <i class="icon ion-md-git-branch icon-md"></i>
            {{ title }}
          </h4>
          <button
            aria-label="Close"
            class="close"
            data-dismiss="modal"
            type="button"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form
          autocomplete="off"
          enctype="multipart/form-data"
          method="POST"
          v-if="withForm"
          v-on:submit.prevent="$emit('sub')"
        >
          <div :class="{ 'form-material': mt }" class="modal-body">
            <slot></slot>
          </div>
          <div class="modal-footer justify-content-center">
            <m-submit :disabled="disabled">
              {{ btnName }}
            </m-submit>
          </div>
        </form>
        <div v-else>
          <div :class="{ 'form-material': mt }" class="modal-body">
            <slot></slot>
          </div>
          <div class="modal-footer justify-content-center" v-show="foot">
            <m-button
              :disabled="disabled"
              @click.native="$emit('save')"
              icon="icon ion-ios-save"
              type="button"
            >
              {{ btnName }}
            </m-button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Modal",
  props: {
    id: {
      type: String,
      default: "myModal"
    },
    mt: {
      type: Boolean,
      default: true
    },
    title: {
      type: String,
      default: "Agregar Nuevo"
    },
    disabled: {
      type: Boolean,
      default: false
    },
    foot: {
      type: Boolean,
      default: true
    },
    btnName: {
      type: String,
      default: "Guardar"
    },
    size: {
      type: String,
      default: ""
    },
    withForm: {
      type: Boolean,
      default: true
    }
  },
  mounted() {
    const self = this;
    $(`#${this.id}`).on("hidden.bs.modal", () => {
      self.$emit("closed");
    });
    $(`#${this.id}`).on("show.bs.modal", () => {
      self.$emit("opened");
    });
  }
};
</script>
