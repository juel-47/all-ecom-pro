import Header from "../components/header/Header";
import Footer from "../components/footer/Footer";
import { usePage, Head } from "@inertiajs/react";
import { useCartStore } from "../stores/cartStore";
import { useEffect } from "react";
import toast, { Toaster } from "react-hot-toast";

const Layout = ({ children }) => {
    const { props } = usePage();
    const { cart } = props;

    const { setCart } = useCartStore();
    
    useEffect(() => {
        if (cart) {
            useCartStore.setState({
                cartCount: cart.count,
                total: cart.total,
            });
        }
    }, [cart]);

    // Toast Flash Management
    useEffect(() => {
        if (props.flash?.success) {
            toast.success(props.flash.success, {
                duration: 4000,
                position: 'top-center',
            });
        }
        if (props.flash?.error) {
            toast.error(props.flash.error, {
                duration: 5000,
                position: 'top-center',
            });
        }
    }, [props.flash]);

    return (
        <>
            <Head title={props.settings?.site_name} />
            <Toaster />
            <Header />
            <main className="overflow-x-hidden">{children}</main>
            <Footer />
        </>
    );
};

export default Layout;
