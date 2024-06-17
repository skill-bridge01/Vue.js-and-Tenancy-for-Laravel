import ConfirmActionModal from './components/ConfirmActionModal'

Nova.booting((app, store) => {
  app.component('data-export-action', ConfirmActionModal)
})
