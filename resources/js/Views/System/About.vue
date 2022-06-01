<template>
  <section style="max-width: 40rem">
    <card :title="`${$store.getters['myapp']}`" :f="false">
      <p>
        Aeduca es una plataforma como servicio (Paas). Está conformado por un
        grupo de herramientas educativas en la nube que cumplen el objetivo de
        centralizar, gestionar y distribuir la información de las instituciones
        educativas
      </p>
      <p>
        <span class="font-weight-bold"> Versión de la aplicación: </span>
        <b class="badge badge-warning p-2"> {{ $store.state.version }} </b>
      </p>
      <p>
        <span class="font-weight-bold"> Año: </span>
        <b>
          {{ new Date().getFullYear() }}
        </b>
      </p>
      <p>
        <span class="font-weight-bold">Fecha de actualización:</span>
        01 de Junio del 2022 a las 12:00 p.m.
      </p>
      <p>
        <span class="font-weight-bold">Desarrollador:</span>
        <a
          class="blue-text"
          href="https://www.facebook.com/ChampiElias"
          target="_blank"
        >
          Elias Champi Hancco
        </a>
      </p>
      <hr />
      <strong>Características Principales</strong>
      <p class="my-2">
        <i class="icon ion-ios-checkmark-circle icon-sm text-success"></i>
        Multi Sede
      </p>
      <p class="my-2">
        <i class="icon ion-ios-checkmark-circle icon-sm text-success"></i>
        Multiusuario: manejo de Autorizacion basado en Roles
      </p>
      <p class="my-2">
        <i class="icon ion-ios-checkmark-circle icon-sm text-success"></i>
        Responsive a dispositivos moviles y de escritorio
      </p>
      <p class="my-2">
        <i class="icon ion-ios-checkmark-circle icon-sm text-success"></i>
        <a
          data-tooltip="Click para ver mas"
          href="https://en.wikipedia.org/wiki/Single-page_application"
          target="__blanck"
        >
          SPA ó (Aplicación de una sola Página)
        </a>
      </p>
      <p class="mt-0">
        <i class="icon ion-ios-checkmark-circle icon-sm text-success"></i>
        Manejo de información segun Intervalos Anuales
      </p>
      <p>
        <i class="text-muted">
          Este proyecto está licenciado bajo la Licencia Apache 2.0.
          <a
            href="https://choosealicense.com/licenses/apache-2.0/"
            target="__black"
          >
            ¿De qué se trata?
          </a>
        </i>
      </p>
    </card>
  </section>
</template>
