import { Route, Routes } from 'react-router-dom'
import Entete from './components/Entete.jsx'
import PiedDePage from './components/PiedDePage.jsx'
import Accueil from './pages/Accueil.jsx'
import Introuvable from './pages/Introuvable.jsx'
import Chambres from './pages/Chambres.jsx'
import ChambreDetail from './pages/ChambreDetail.jsx'

function App() {
    return (
    <>
      <Entete />
      <main>
        <Routes>
          <Route path="/" element={<Accueil />} />
          <Route path="/chambres" element={<Chambres />} />
          <Route path="/chambres/:id" element={<ChambreDetail />} />
          <Route path="*" element={<Introuvable />} />
        </Routes>
      </main>
      <PiedDePage />
    </>
  )
}

export default App
