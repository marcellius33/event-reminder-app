import { createBrowserRouter, RouterProvider } from "react-router-dom";
import LoginPage from "./pages/LoginPage";
import RegisterPage from "./pages/RegisterPage";
import EventPage from "./pages/EventPage";

function App() {
  return (
    <>
      <RouterProvider
        router={createBrowserRouter([
          {
            path: "/",
            element: <LoginPage />,
          },
          {
            path: "/register",
            element: <RegisterPage />,
          },
          {
            path: "/event",
            element: <EventPage />,
          },
        ])}
      />
    </>
  );
}

export default App;
