import '@admin/styles/admin.css'

import CorbidevModal from './components/modal'
import { initRepositoryManager } from './components/repositoryManager'
import { initRepositoryInstaller } from './components/repositoryInstaller'
import eruda from 'eruda'

    eruda.init()

// Init modules
initRepositoryManager()
initRepositoryInstaller()

// Modal global si besoin ailleurs
window.corbidevModal = new CorbidevModal()