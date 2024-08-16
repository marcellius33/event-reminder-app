import { createBrowserRouter, RouterProvider } from "react-router-dom";
import LoginPage from "./pages/LoginPage";
import RegisterPage from "./pages/RegisterPage";
import EventPage from "./pages/EventPage";

function App() {
  // TODO: Check auth here, if there is valid token, redirect to events page if not login page
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
