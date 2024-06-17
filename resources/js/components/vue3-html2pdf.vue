<template>
  <div class="vue-html2pdf">
    <section
      class="layout-container"
      :class="{
        'show-layout': showLayout,
        'unset-all': !floatLayout,
      }"
    >
      <section
        class="content-wrapper"
        :style="`width: ${pdfContentWidth};`"
        ref="pdfContent"
      >
        <slot name="pdf-content" />
      </section>
    </section>

    <transition name="transition-anim">
      <section class="pdf-preview" v-if="pdfFile">
        <button @click.self="closePreview()">&times;</button>

        <iframe :src="pdfFile" width="100%" height="100%" />
      </section>
    </transition>
  </div>
</template>
<script>
import html2pdf from 'html2pdf.js'

export default {
  props: {
    showLayout: {
      type: Boolean,
      default: false,
    },

    floatLayout: {
      type: Boolean,
      default: true,
    },

    enableDownload: {
      type: Boolean,
      default: true,
    },

    previewModal: {
      type: Boolean,
      default: false,
    },

    paginateElementsByHeight: {
      type: Number,
    },

    filename: {
      type: String,
      default: `${new Date().getTime()}`,
    },

    pdfQuality: {
      type: Number,
      default: 2,
    },

    pdfFormat: {
      default: 'a4',
    },

    pdfOrientation: {
      type: String,
      default: 'portrait',
    },

    pdfContentWidth: {
      default: '800px',
    },

    htmlToPdfOptions: {
      type: Object,
    },

    manualPagination: {
      type: Boolean,
      default: false,
    },
  },

  data() {
    return {
      hasAlreadyParsed: false,
      progress: 0,
      pdfWindow: null,
      pdfFile: null,
    }
  },

  watch: {
    progress(val) {
      this.$emit('progress', val)
    },

    paginateElementsByHeight() {
      this.resetPagination()
    },

    $props: {
      handler() {
        this.validateProps()
      },

      deep: true,
      immediate: true,
    },
  },

  methods: {
    validateProps() {
      // If manual-pagination is false, paginate-elements-by-height props is required
      if (!this.manualPagination) {
        if (this.paginateElementsByHeight === undefined) {
          console.error(
            'Error: paginate-elements-by-height is required if manual-pagination is false'
          )
        }
      }
    },

    resetPagination() {
      const parentElement = this.$refs.pdfContent // .firstChild
      const pageBreaks = parentElement.getElementsByClassName(
        'html2pdf__page-break'
      )
      const pageBreakLength = pageBreaks.length - 1

      if (pageBreakLength === -1) return

      this.hasAlreadyParsed = false

      // Remove All Page Break (For Pagination)
      for (let x = pageBreakLength; x >= 0; x--) {
        pageBreaks[x].parentNode.removeChild(pageBreaks[x])
      }
    },

    generatePdf() {
      this.$emit('startPagination')
      this.progress = 0
      this.paginationOfElements()
    },

    paginationOfElements() {
      this.progress = 25

      /* When this props is true, the props paginate-elements-by-height will not be used. Instead the pagination process will rely on the elements with a class "html2pdf__page-break" to know where to page break, which is automatically done by html2pdf.js */
      if (this.manualPagination) {
        this.progress = 70
        this.$emit('hasPaginated')
        this.downloadPdf()
        return
      }

      if (!this.hasAlreadyParsed) {
        const parentElement = this.$refs.pdfContent // .firstChild
        const ArrOfContentChildren = Array.from(this.$refs.pdfContent.children)

        let childrenHeight = 0

        /* Loop through Elements and add there height with childrenHeight variable. Once the childrenHeight is >= this.paginateElementsByHeight, create a div with a class named 'html2pdf__page-break' and insert the element before the element that will be in the next page */
        for (const childElement of ArrOfContentChildren) {
          // Get The First Class of the element
          const elementFirstClass = childElement.classList[0]
          const isPageBreakClass = elementFirstClass === 'html2pdf__page-break'
          if (isPageBreakClass) {
            childrenHeight = 0
          } else {
            // Get Element Height
            const elementHeight = childElement.clientHeight

            // Get Computed Margin Top and Bottom
            const elementComputedStyle =
              childElement.currentStyle || window.getComputedStyle(childElement)
            const elementMarginTopBottom =
              parseInt(elementComputedStyle.marginTop) +
              parseInt(elementComputedStyle.marginBottom)

            // Add Both Element Height with the Elements Margin Top and Bottom
            const elementHeightWithMargin =
              elementHeight + elementMarginTopBottom

            if (
              childrenHeight + elementHeight <
              this.paginateElementsByHeight
            ) {
              childrenHeight += elementHeightWithMargin
            } else {
              const section = document.createElement('div')
              section.classList.add('html2pdf__page-break')
              parentElement.insertBefore(section, childElement)

              // Reset Variables made the upper condition false
              childrenHeight = elementHeightWithMargin
            }
          }
        }

        this.progress = 70

        /* Set to true so that if would generate again we wouldn't need to parse the HTML to paginate the elements */
        this.hasAlreadyParsed = true
      } else {
        this.progress = 70
      }

      this.$emit('hasPaginated')
      this.downloadPdf()
    },

    async downloadPdf() {
      // Set Element and Html2pdf.js Options
      const pdfContent = this.$refs.pdfContent
      const options = this.setOptions()

      this.$emit('beforeDownload', { html2pdf, options, pdfContent })

      const html2PdfSetup = html2pdf().set(options).from(pdfContent)
      let pdfBlobUrl = null

      if (this.previewModal) {
        this.pdfFile = await html2PdfSetup.output('bloburl')
        pdfBlobUrl = this.pdfFile
      }

      if (this.enableDownload) {
        pdfBlobUrl = await html2PdfSetup.save().output('bloburl')
      }

      if (pdfBlobUrl) {
        const res = await fetch(pdfBlobUrl)
        const blobFile = await res.blob()
        this.$emit('hasDownloaded', blobFile)
      }

      this.progress = 100
    },

    setOptions() {
      if (
        this.htmlToPdfOptions !== undefined &&
        this.htmlToPdfOptions !== null
      ) {
        return this.htmlToPdfOptions
      }

      return {
        margin: 0,

        filename: `${this.filename}.pdf`,

        image: {
          type: 'jpeg',
          quality: 0.98,
        },

        enableLinks: false,

        html2canvas: {
          scale: this.pdfQuality,
          useCORS: true,
        },

        jsPDF: {
          unit: 'in',
          format: this.pdfFormat,
          orientation: this.pdfOrientation,
        },
      }
    },

    closePreview() {
      this.pdfFile = null
    },
  },
}
</script>

<style lang="scss" scoped>
.vue-html2pdf {
  .layout-container {
    position: fixed;
    width: 100vw;
    height: 100vh;
    left: -100vw;
    top: 0;
    z-index: -9999;
    background: rgba(95, 95, 95, 0.8);
    display: flex;
    justify-content: center;
    align-items: flex-start;
    overflow: auto;

    &.show-layout {
      left: 0vw;
      z-index: 9999;
    }

    &.unset-all {
      all: unset;
      width: auto;
      height: auto;
    }
  }

  .pdf-preview {
    position: fixed;
    width: 65%;
    min-width: 600px;
    height: 80vh;
    top: 100px;
    z-index: 9999999;
    left: 50%;
    transform: translateX(-50%);
    border-radius: 5px;
    box-shadow: 0px 0px 10px #00000048;

    button {
      position: absolute;
      top: -20px;
      left: -15px;
      width: 35px;
      height: 35px;
      background: #555;
      border: 0;
      box-shadow: 0px 0px 10px #00000048;
      border-radius: 50%;
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      cursor: pointer;
    }

    iframe {
      border: 0;
    }
  }

  .transition-anim-enter-active,
  .transition-anim-leave-active {
    transition: opacity 0.3s ease-in;
  }

  .transition-anim-enter,
  .transition-anim-leave-to {
    opacity: 0;
  }
}

.invoice-wrapper {
  width: 485px;
  border-radius: 24px;
  background: var(--neutrals-text, #737F98);
  display: inline-flex;
  padding: 36px;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  gap: 24px;

  & .content-wrapper {
    display: flex;
    padding: 23px 21px;
    flex-direction: column;
    justify-content: center;
    align-items: flex-end;
    gap: 24px;

    .company-header {
      display: flex;
      padding: 29px 40px;
      align-items: center;
      gap: 24px;
      border-radius: 16px;
      background: var(--neutrals-sky-gray, #ECF1F4);

      .company-name {
        color: #000;
        text-align: right;
        font-size: 12px;
        font-family: Readex Pro;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
      }

      .company-logo {
        display: flex;
        width: 40px;
        height: 40px;
        justify-content: center;
        align-items: center;
        img {
          background-blend-mode: luminosity;
        }
      }
    }

    .invoice-title-wrapper {
      display: flex;
      flex-direction: column;
      align-self: stretch;
      color: #000;
      text-align: center;
      font-size: 15px;
      font-family: Readex Pro;
      font-style: normal;
      font-weight: 400;
      line-height: normal;
    }

    .line {
      width: 357px;
      height: 1px;
      background: #ECF1F4;
    }
    .customr-info {
      color: #000;
      text-align: center;
      font-size: 12px;
      font-family: Readex Pro;
      font-style: normal;
      font-weight: 400;
      line-height: normal;
    }

    .invoice-table-wrapper {
      width: 100%;
      border-radius: 16px;
      border: 1px solid var(--neutrals-sky-gray, #ECF1F4);

      table {
        border-style: none;
        th {
          color: var(--primary-dark-blue-03, #737F98);
          text-align: right;
          font-size: 10px;
          font-family: Readex Pro;
          font-style: normal;
          font-weight: 500;
          line-height: normal;
          text-align: right;
        }
        td {
          color: var(--neuturals-medium-dark, #484860);
          text-align: right;
          font-size: 10px;
          font-family: Readex Pro;
          font-style: normal;
          font-weight: 500;
          line-height: normal;
        }
      }
    }

    .note-wrapper {
      display: flex;
      align-items: flex-start;
      gap: 8px;
      color: #000;
      text-align: center;
      font-size: 12px;
      font-family: Readex Pro;
      font-style: normal;
      font-weight: 400;
      line-height: normal;
    }

    .sign-wrapper {
      display: flex;
      flex-direction: column;
      align-items: flex-end;
      gap: 8px;
    }

    .footer {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 8px;
      align-self: stretch;
      color: #000;
      text-align: center;
      font-size: 12px;
      font-family: Readex Pro;
      font-style: normal;
      font-weight: 400;
      line-height: normal;

      .social-wrapper {
        display: flex;
        align-items: center;
        gap: 8px;
      }
    }
  }

  & .button-wrapper {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 8px;

    .print-label {
      color: #FFF;

      /* 14/Regular */
      font-size: 14px;
      font-family: Readex Pro;
      font-style: normal;
      font-weight: 400;
      line-height: normal;
    }
    .download-label {
      color: #484860;

      /* 14/Regular */
      font-size: 14px;
      font-family: Readex Pro;
      font-style: normal;
      font-weight: 400;
      line-height: normal;
    }
  }
}
</style>