import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm, Link  } from '@inertiajs/inertia-react';
import { Inertia } from '@inertiajs/inertia'
import { useEffect, useState } from "react";
import InputError from '@/Components/InputError';

const Index = (props) => {
  const [dragIndex, setDragIndex] = useState(null);
  const { data, setData, patch, errors } = useForm({users: props.users});

  const pushUser = () => {
    setData((prevData) => {
      let newUsers = [...prevData.users];
      newUsers.push({
        id: 0,
        name: "",
        username: "",
        email: ""
      });
      return {users: newUsers};
    });
  };

  const dragStart = (index) => {
    setDragIndex(index);
  };

  const dragEnter = (index) => {
    if (index === dragIndex) return;
    setData((prevData) => {
      let newUsers = [...prevData.users];
      const deleteElement = newUsers.splice(dragIndex ?? 0, 1)[0];
      newUsers.splice(index, 0, deleteElement);
      return {users: newUsers};
    });
    setDragIndex(index);
  };

  const dragEnd = () => {
    setDragIndex(null);
  };

  const sendUsers = () => {
    console.table(data.users);
    patch(route('users.multiupdate'), {
      // onSuccess: () => setData({users: props.users}),
    })
  };

  const setUserAttr = (event, index) => {
    setData((prevData) => {
      let newUsers = [...prevData.users];
      newUsers[index][event.target.name] = event.target.value;
      return {users: newUsers};
    });
  };
  return (
    <AuthenticatedLayout
      auth={props.auth}
      errors={props.errors}
      header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">ユーザー一覧</h2>}
    >
    <Head title="ユーザー一覧" />

    <div className="py-12">
      <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div style={{ margin: "2em" }}>
          <button onClick={pushUser}>ユーザー追加</button>
          <button onClick={sendUsers}>変更を確定</button>
          {props.flash?.message}
          {props.flash?.status}
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>名前</th>
                <th>ユーザ名</th>
                <th>Email</th>
              </tr>
            </thead>
            <tbody>
              {data.users.map((user, index) => (
                <tr
                  key={index}
                  draggable={true}
                  onDragStart={() => dragStart(index)}
                  onDragEnter={() => dragEnter(index)}
                  onDragOver={(e) => e.preventDefault()}
                  onDragEnd={dragEnd}
                  className={index === dragIndex ? "dragging" : ""}
                >
                  <td>{user.id !== 0 ? user.id : null}</td>
                  <td>
                    <input
                      type="text"
                      value={user.name}
                      name="name"
                      onChange={(event) => setUserAttr(event, index)}
                    />
                    <InputError message={errors[`users.${index}.name`]}/>
                  </td>
                  <td>
                    <input
                      type="text"
                      value={user.username}
                      name="username"
                      onChange={(event) => setUserAttr(event, index)}
                    />
                    <InputError message={errors[`users.${index}.username`]}/>
                  </td>
                  <td>
                    <input
                      type="text"
                      value={user.email}
                      name="email"
                      onChange={(event) => setUserAttr(event, index)}
                    />
                    <InputError message={errors[`users.${index}.email`]}/>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
  );
};
export default Index;